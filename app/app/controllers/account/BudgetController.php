<?php
/**
 * Class and Function List:
 * Function list:
 * - __construct()
 * - getIndex()
 * Classes list:
 * - BudgetController extends BaseController
 */
class BudgetController extends BaseController {
    /**
     * Budget Model
     * @var accounts
     */
    protected $budget;
    /**
     * User Model
     * @var User
     */
    protected $user;
    /**
     * Inject the models.
     * @param Account $account
     * @param User $user
     */
    public function __construct(Budget $budget, User $user) 
    {
        parent::__construct();
        $this->user = $user;
        $this->budget = $budget;
    }


    public function getIndex() 
    {
        // Get all the user's budget accounts
        //Auth::id() : gives the logged in userid
        $budgets = $this->budget
                                -> where('budget.user_id', Auth::id())
                                -> join('cloudAccounts', 'cloudAccounts.id', '=', 'budget.cloudAccountId')
                                -> select('budget.*', 'cloudAccounts.name','cloudAccounts.cloudProvider','cloudAccounts.status','cloudAccounts.profileType')
                                -> orderBy('budget.created_at', 'DESC')
                                -> paginate(10);

        if(!Auth::check())
        {
            Log::error("Not authorized access");
            return Redirect::to('budget')->with('error', Lang::get('budget/budget.budget_auth_failed'));
        }

        return View::make('site/account/budget/index', array(

                                             'budgets' => $budgets
            
                                               ));
    }     

    /**
     * Displays the form for budget creation
     *
     */
    public function getCreate($id = false) {

        $mode        = $id !== false ? 'edit' : 'create';
        $budget      = $id !== false ? Budget::where('user_id', Auth::id())->findOrFail($id) : null;
        $budgetType  = array('weekly', 'monthly');
		
		if($id !== false)
        {
        	$accounts    = CloudAccount::where('user_id', Auth::id())->where('id', $budget->cloudAccountId)->where('profileType', Lang::get('security/portPreferences.readonlyProfile'))->get();
		}
		else 
        {
			$accounts    = CloudAccount::where('user_id', Auth::id())->where('profileType', Lang::get('security/portPreferences.readonlyProfile'))->get();
		}
        return View::make('site/account/budget/create_edit', compact('mode', 'budget', 'budgetType', 'accounts'));
    }


    /**
     * Saves/Edits an account
     *
     */
    public function postEdit($id = false) {

       if($id !== false)
            $budget = Budget::where('user_id', Auth::id())->findOrFail($id);
    
        try {
            if (empty($budget)) {
                $budget = new Budget;
            } else if ($budget->user_id !== Auth::id()) {
                throw new Exception('general.access_denied');
            }

            $cloudAccountId                  = Input::get('cloudAccountId');
            $budget->cloudAccountId          = empty($cloudAccountId) ? 0 : $cloudAccountId;
            $budget->budgetType              = Input::get('budgetType');
            $budget->budget                  = Input::get('budget');
            $budget->budgetNotificationEmail = Input::get('budgetNotificationEmail');
            $budget->user_id                 = Auth::id();

            $success = $budget->save();

            $account = CloudAccount::where('user_id', Auth::id())->find($budget -> cloudAccountId);
           

            $account->profileType = Constants::BUDGET;


            $processJobLib = new ProcessJobLib();
            $ret = $processJobLib->process($account, $budget);
            
            return Redirect::intended('budget')->with('success', Lang::get('budget/budget.budget_updated'));
           
        }
        catch(Exception $e) {
            Log::error($e);
            return Redirect::to('budget')->with('error', $e->getMessage());
        }
    }


     public function postDelete($id) {
        
        Budget::where('id', $id)->where('user_id', Auth::id())->delete();

        $budget = Budget::where('user_id', Auth::id())->find($id);

        if (empty($budget)) {

            return Redirect::to('budget')->with('success', 'Removed Budget Account Successfully');
        } else {

            return Redirect::to('budget')->with('error', 'Error while deleting Budget Account');
        }
    }

    public function getBudgetStatus($id)
    {
      UtilHelper::check();
     
      $account = CloudAccountHelper::findAndDecrypt($id);
      $responseJson = AWSBillingEngine::authenticate(array('username' => Auth::user()->username, 'password' => md5(Auth::user()->engine_key)));
      EngineLog::logIt(array('user_id' => Auth::id(), 'method' => 'authenticate', 'return' => $responseJson));
      $obj = WSObj::getObject($responseJson);

      if($obj->status == 'OK')
      {
         Log::info('Preparing the ServiceSummary for processing..');
         $credentials           = json_decode($account->credentials);
         $data['token']         = $obj->token;
         $data['accountId']     = $credentials->accountId;

         $json = AWSBillingEngine::getBudgetStatus($data);
         $ret = WSObj::getObject($responseJson);        
         $ret = json_decode($json);
         
       /*
        @Hard Coding for testing purpose 

         $ret1['status'] = 'OK';
         $ret1['last_alert'] = array('username' => 'sudhi',
            'accountId' => '297860697318','last_update' => '1419449459','current_budget' => '2451.59',);
        */
        


         if($ret->status == 'OK')
         {
              if(!empty($ret->last_alert))
              { 
                foreach ($ret->last_alert as $key => $value) 
                {
                  $budgetstatus[$key]   = empty($value)         ? '' : $value;
                }

                Log::info('GetBudgetStatus Generated Successfully');     
                return View::make('site/account/budget/budgetStatus', array('account' => $account,
                'budgetstatus'=> $budgetstatus));
              }
              Log::error($ret->last_alert.' '.json_encode($account));
              return Redirect::to('budget')->with('warning', 'No History available on Last Alert');
              
        }  
          else if($ret->status == 'error')
          {
            Log::error($ret->message.' '.json_encode($account));
            RedirectHelper::redirectAccount(Constants::FAILURE);
          }
       }
       else
       {
          RedirectHelper::redirectAccount(Constants::ENGINE_CREDENTIALS_FAILURE);
       }
    }

}

