<?php
/**
 * Class and Function List:
 * Function list:
 * - __construct()
 * - getIndex()
 * - getCreate()
 * - postEdit()
 * - postDelete()
 * Classes list:
 * - PortPreferencesController extends BaseController
 */
class PortPreferencesController extends BaseController {
    
	 /**
     * User Model
     * @var User
     */
    private $portPreference;
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
    public function __construct(PortPreference $portPreference, User $user) {
        parent::__construct();
        $this->portPreference = $portPreference;
        $this->user = $user;
    }
    /**
     * Returns all the Accounts for logged in user.
     *
     * @return View
     */
    public function getIndex() {
        // Get all the user's accounts
        //Auth::id() : gives the logged in userid
        $portPreferences = $this->portPreference
        						-> where('portPreferences.user_id', Auth::id())
        						-> join('cloudAccounts', 'cloudAccounts.id', '=', 'portPreferences.cloudAccountId')
        						->select('portPreferences.*', 'cloudAccounts.name','cloudAccounts.cloudProvider','cloudAccounts.status','cloudAccounts.profileType')
								-> orderBy('portPreferences.created_at', 'DESC')
        						-> paginate(10);
		
		/*DB::table('portPreferences')
            ->join('portPreferences', 'users.id', '=', 'portPreferences.user_id')
            ->join('cloudAccounts', 'cloudAccounts.id', '=', 'portPreferences.cloudAccountId')
            ->select('portPreferences.id', 'portPreferences.name', 'cloudAccounts.name as Account')
           -> orderBy('created_at', 'DESC')
           -> paginate(10);
		*/
		return View::make('site/security/portPreferences/index', array(
            'portPreferences' => $portPreferences
        ));
    }
    /**
     * Displays the form for PortPreference creation
     *
     */
    public function getCreate($id = false) {
        $mode = $id !== false ? 'edit' : 'create';
		$portPreference =  $id !== false ? PortPreference::where('user_id', Auth::id()) ->find($id) : null;
		$accounts = CloudAccount::where('user_id', Auth::id()) -> where('profileType', Constants::READONLY_PROFILE)->get();
		$portSchema = Config::get('port_schema');
        return View::make('site/security/portPreferences/create_edit', compact('mode', 'accounts', 'portPreference', 'portSchema'));
    }
    /**
     * Saves/Edits an account
     *
     */
    public function postEdit($id = false) {
    	if($id !== false)
    		$portPreference = PortPreference::where('user_id', Auth::id())->findOrFail($id);
        
        try {
            	if (empty($portPreference)) {
                $portPreference = new PortPreference;
            	} else if ($portPreference->user_id !== Auth::id()) {
                	throw new Exception('general.access_denied');
            	}
		    
	            $portPreference->project = Input::get('project');
				$preferences = Input::get('preferences');
				$this->validatePreferences($preferences, $id);
				$portPreference -> cloudAccountId = Input::get('cloudAccountId');
	            $portPreference->preferences = json_encode($preferences);
	            $portPreference->user_id = Auth::id(); // logged in user id
	            $portPreference->save();

				$account = CloudAccount::where('user_id', Auth::id())->find($portPreference -> cloudAccountId);
				$processJobLib = new ProcessJobLib();
				$ret = $processJobLib->process($account, $portPreference);

				return Redirect::intended('security/portPreferences')->with('success', Lang::get('security/portPreferences.portPreference_updated'));
         }
        catch(Exception $e) {
            Log::error($e);
            return Redirect::to('security/portPreferences')->with('error', $e->getMessage());
        }
    }

	private function validatePreferences($preferences)
	{
		$errors = '';
		foreach($preferences as $prefernce => $ports)
		{
			$array = explode(',', $ports);
			$ret = array_filter($array, 'is_numeric') === $array;
			if(!$ret)
			{
				$errors[] = StringHelper::removeUnderscoreUCWords($prefernce) . ' should have all numeric values separated by commas';
			}
		}
	
		if(!empty($errors))
		{
			 Log::error(json_encode($errors));
           	 throw new Exception(implode('<br/>', $errors));
		}
		return;
	}

	 /** 
	 * 
	 * Remove the specified Account .
     *
     * @param $portPreference
     *
     */
    public function postDelete($id) {
    		
    	PortPreference::where('id', $id)->where('user_id', Auth::id())->delete();
        
        // Was the comment post deleted?
        $portPreference = PortPreference::where('user_id', Auth::id())->find($id);
        if (empty($portPreference)) {
            // TODO needs to delete all of that user's content
            return Redirect::to('security/portPreferences')->with('success', 'Removed Port preference Successfully');
        } else {
            // There was a problem deleting the user
            return Redirect::to('security/portPreferences/' . $portPreference->id . '/edit')->with('error', 'Error while deleting');
        }
    }

    private function check($json = false)
	{
		if($json)
		{
			if(AWSBillingEngine::getServiceStatus() == 'error')
			{
				Log::error(Lang::get('account/account.awsbilling_service_down'));
				print json_encode(array('status' => 'error', 'message' => Lang::get('account/account.awsbilling_service_down')));
				return;
			}
		}
		else 
		{
			
			if(AWSBillingEngine::getServiceStatus() == 'error')
			{
				Log::error(Lang::get('account/account.awsbilling_service_down'));
				print json_encode(array('status' => 'error', 'message' => Lang::get('account/account.awsbilling_service_down')));
				return;
			}
		}
	}

    public function checkStatus($id)
	{
		$this->check();
		$portPreference = PortPreference::where('user_id', Auth::id())->find($id);



		if(empty($portPreference))
		{
			return Redirect::to('security/portPreferences')->with('info', 'Selected portPreference do not need refresh!');
		}
		$user = Auth::user();

		$responseJson = AWSBillingEngine::authenticate(array('username' => $user->username, 'password' => md5($user->engine_key)));
		EngineLog::logIt(array('user_id' => Auth::id(), 'method' => 'authenticate', 'return' => $responseJson));
		$obj = json_decode($responseJson);

		if(!empty($obj) && $obj->status == 'OK')
		{
			$responseJson = AWSBillingEngine::getDeploymentStatus(array('token' => $obj->token, 'job_id' => $portPreference->job_id));
			EngineLog::logIt(array('user_id' => Auth::id(), 'method' => 'getDeploymentStatus', 'return' => $responseJson));
		
			$obj2 = json_decode($responseJson);

			if(!empty($obj2) && $obj2->status == 'OK')
			{
				if(!isset($obj2 -> result))
				{
					Log::error('No Result in the checkStatus Request to be saved!');
					$obj2 -> result ='';
				} 
				
				$portPreference->status = $obj2->job_status;
				$portPreference -> wsResults = json_encode($obj2 -> result);
				$success = $portPreference->save();
				
		        if (!$success) {
		        	Log::error('Error while saving portPreference Log : '.json_encode( $portPreference->errors()));
					return Redirect::to('security/portPreferences')->with('error', 'Error saving portPreference!' );
		        }
				return Redirect::to('security/portPreferences')->with('success', $portPreference->name .' is refreshed' );
			}
			else  if(!empty($obj2) && $obj2->status == 'error')
			 {
			 	$portPreference->status = $obj2->job_status;
				$portPreference -> wsResults = json_encode($obj2 -> result);
				$success = $portPreference->save();
				
				if (!$success) {
		        	Log::error('Error while saving portPreference : '.json_encode( $portPreference->errors()));
					return Redirect::to('security/portPreferences')->with('error', 'Error saving portPreference!' );
		        }
				 // There was a problem deleting the user
				Log::error($responseJson);
				Log::error('Request to check status of portPreference failed :' . $obj2->fail_code . ':' . $obj2->fail_message);
				return Redirect::to('security/portPreferences')->with('error', 'Error while checking for status of portPreference' );
			 }	
			else
			{
				  return Redirect::to('ServiceStatus')->with('error', 'Backend API is down, please try again later!');			
			}
			
		 }	
		 else if(!empty($obj) && $obj->status == 'error')
		 {
			Log::error('Request to check status of portPreference failed :' . $obj->fail_code . ':' . $obj->fail_message);
			return Redirect::to('security/portPreferences')->with('error', $obj->fail_message );
		 }	
		 else
		 {
			return Redirect::to('ServiceStatus')->with('error', 'Backend API is down, please try again later!');			
		 }	
	}


	public function portInfo($id)
	{

			$this->check();
		  	$account = CloudAccountHelper::findAndDecrypt($id);

		  	$responseJson = AWSBillingEngine::authenticate(array('username' => Auth::user()->username, 'password' => md5(Auth::user()->engine_key)));
			EngineLog::logIt(array('user_id' => Auth::id(), 'method' => 'authenticate - portScan', 'return' => $responseJson));
			$obj = json_decode($responseJson);

			if($obj->status == 'OK')
			{
				Log::info('Preparing the Port Info for processing..');
				$credentials 	 	= json_decode($account->credentials);
				
				$data['token'] 	 	= $obj->token;
				$data['accountId'] 	= $credentials->accountId;
				
				
				$json = AWSBillingEngine::SecgroupReport($data);
				Log::info('Adding the job to port Pref queue for processing..'.$json);
								
			if(StringHelper::isJson($json))
			{
				$ret = json_decode($json);
				if($ret->status == 'OK')
				{
										
					if(!empty($ret->report->sec_groups))
					{
						$arr = array();$i=0;
						foreach ($ret->report->sec_groups as $key => $value) {
						

						$arr[$i]['Security Group Name']                            =$key;
						if(empty($value->safe_ports)) $arr[$i]['Safe Ports']       = ''; else  $arr[$i]['Safe Ports'] = implode(" | ",$value->safe_ports); 
						if(empty($value->warning_ports)) $arr[$i]['Warning Ports'] = ''; else  $arr[$i]['Warning Ports'] = implode(" | ",$value->warning_ports); 
						if(empty($value->danger_ports)) $arr[$i]['Danger Ports']   = ''; else  $arr[$i]['Danger Ports'] = implode(" | ",$value->danger_ports); 
						if(empty($value->instances)) $arr[$i]['Instance']          = ''; else  $arr[$i]['Instance'] = $value->instances; 

						$i++;
							
						}
					}
					return View::make('site/security/portPreferences/portInfo', array('account' => $account,
											'portDetails'=> $arr));
			    }
				else if($ret->status == 'error')
				{
					Log::error('Failed to return security group'.$ret->status);
					Redirect::to('security/portPreferences')->with('error', $ret->status);
				}
			}
			else {
				Log::error('Failed could not add to queue');
				Redirect::to('security/portPreferences')->with('error', 'Invalid Json returned');
			}
		}
		else
			{
				Log::error('Engine credentials mis-match. Contact support team.');
				Redirect::to('security/portPreferences')->with('error', 'Engine credentials mis-match. Contact support team.');
	
			}	
		
	}
	
}
