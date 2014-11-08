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
 * - AccountController extends BaseController
 */
class AccountController extends BaseController {
    /**
     * CloudAccount Model
     * @var accounts
     */
    protected $accounts;
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
    public function __construct(CloudAccount $accounts, User $user) {
        parent::__construct();
        $this->accounts = $accounts;
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
        $accounts = $this->accounts->where('user_id', Auth::id())->orderBy('created_at', 'DESC')->paginate(10);
		
		$data= '';
		foreach($accounts as $account)
		{
			$account -> currentCost  = CloudAccountHelper::findCurrentCost($account);
			$data[] = $account;
		}
        // var_dump($accounts, $this->accounts, $this->accounts->owner);
        // Show the page
        return View::make('site/account/index', array(
            'accounts' => $data
        ));
    }
    /**
     * Displays the form for cloud account creation
     *
     */
    public function getCreate($id = false) {
        $mode = $id !== false ? 'edit' : 'create';
		$account =  $id !== false ? CloudAccountHelper::findAndDecrypt($id) : null;
		$providers = Config::get('account_schema');
        return View::make('site/account/create_edit', compact('mode', 'account', 'providers'));
    }
    /**
     * Saves/Edits an account
     *
     */
    public function postEdit($id = false) {
    	if($id !== false)
    		$account = CloudAccount::where('user_id', Auth::id())->findOrFail($id);
        try {
            if (empty($account)) {
                $account = new CloudAccount;
            } else if ($account->user_id !== Auth::id()) {
                throw new Exception('general.access_denied');
            }
		    
            $account->name = Input::get('name');
            $account->cloudProvider = Input::get('cloudProvider');
            $account->credentials = json_encode(Input::get('credentials'));
            $account->user_id = Auth::id(); // logged in user id
            
            $conStatus = CloudProvider::authenticate($account);
            
            
            if ($conStatus == 1) {
            	Log::info('Credentials are encrypted before saving to DB.');
				$ret = $this->process($account);
				CloudAccountHelper::save($account);
				
				return $this->redirect($ret);
            	//return Redirect::intended('account')->with('success', Lang::get('account/account.account_updated'));
            } else {
                return Redirect::to('account')->with('error', Lang::get('account/account.account_auth_failed'));
            }
        }
        catch(Exception $e) {
            Log::error($e);
            return Redirect::to('account')->with('error', $e->getMessage());
        }
    }


	private function redirect($state)
	{
		$ret = '';
		switch ($state)
		{
			case 'SUCCESS': $ret = Redirect::intended('account')->with('success', Lang::get('account/account.account_updated')); break;
			case 'BAD_CREDENTIALS':
			case 'FAILURE' : $ret = Redirect::to('account')->with('error', 'Check Account Credentials!'); break;
			case 'ENGINE_FAILURE' : $ret =  Redirect::to('account')->with('error', 'Check if AWS Usage Processing engine is up!'); break;
			case 'ENGINE_CREDENTIALS_FAILURE' : $ret =  Redirect::to('account')->with('error', 'Engine credentials mis-match. Contact support team.'); break;
		}	
		return $ret;
	}
	
	private function process(& $account)
	{
		$responseJson = AWSBillingEngine::authenticate(array('username' => Auth::user()->username, 'password' => md5(Auth::user()->engine_key)));
		EngineLog::logIt(array('user_id' => Auth::id(), 'method' => 'authenticate', 'return' => $responseJson));
		$obj = json_decode($responseJson);
		
		if(!StringHelper::isJson($responseJson))
		{
			return 'ENGINE_CREDENTIALS_FAILURE';
		}
				
		if($obj->status == 'OK')
		{
			Log::info('Preparing the account for processing..');
			$credentials 	 	= json_decode($account->credentials);
			$data['token'] 	 	= $obj->token;
			$data['apiKey'] 	= StringHelper::encrypt($credentials ->apiKey, md5(Auth::user()->username));
			$data['secretKey'] 	= StringHelper::encrypt($credentials ->secretKey, md5(Auth::user()->username));
			$data['accountId'] 	= $credentials->accountId;
			$data['billingBucket'] = $credentials->billingBucket;
			$json = AWSBillingEngine::create_billing($data);
			Log::info('Adding the job to billing queue for processing..'.$json);
			
			if(StringHelper::isJson($json))
			{
				$ret = json_decode($json);
				if($ret->status == 'OK')
				{
					$account ->status =' In process';
					$account->job_id = $ret->job_id;
					Log::info('Job Id:'.$ret->job_id);
					return 'SUCCESS';
				}
				else if($ret->status == 'error')
				{
					$account ->status = $ret->status;
					$account->job_id = '';
					Log::error($ret->message.' '.json_encode($account));
					return 'FAILURE';
				}
			}
			else {
				Log::error('Failed to add to billing queue'.json_encode($account));
				return 'BAD_CREDENTIALS';
			}
		}
		else
			{
				return 'ENGINE_CREDENTIALS_FAILURE';
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
		$account = CloudAccount::where('user_id', Auth::id())->find($id);
		if(empty($account))
		{
			return Redirect::to('account')->with('info', 'Selected Account do not need refresh!');
		}
		$user = Auth::user();
		$responseJson = AWSBillingEngine::authenticate(array('username' => $user->username, 'password' => md5($user->engine_key)));
		EngineLog::logIt(array('user_id' => Auth::id(), 'method' => 'authenticate', 'return' => $responseJson));
		$obj = json_decode($responseJson);
		
		if(!empty($obj) && $obj->status == 'OK')
		{
			$responseJson = AWSBillingEngine::getDeploymentStatus(array('token' => $obj->token, 'job_id' => $account->job_id));
			EngineLog::logIt(array('user_id' => Auth::id(), 'method' => 'getDeploymentStatus', 'return' => $responseJson));
		
			$obj2 = json_decode($responseJson);
			if(!empty($obj2) && $obj2->status == 'OK')
			{
				if(!isset($obj2 -> result))
				{
					Log::error('No Result in the checkStatus Request to be saved!');
					$obj2 -> result ='';
				} 
				$account->status = $obj2->job_status;
				$account -> wsResults = json_encode($obj2 -> result);
				$success = $account->save();
		        if (!$success) {
		        	Log::error('Error while saving Account : '.json_encode( $dep->errors()));
					return Redirect::to('account')->with('error', 'Error saving Account!' );
		        }
				return Redirect::to('account')->with('success', $account->name .' is refreshed' );
			}
			else  if(!empty($obj2) && $obj2->status == 'error')
			 {
				 // There was a problem deleting the user
				 Log::error('Request to check status of account failed :' . $obj2->fail_code . ':' . $obj2->fail_message);
				 Log::error('Log :' . implode(' ', $obj2->job_log));
	            return Redirect::to('account')->with('error', $obj2->fail_message );
			 }	
			else
			{
				  return Redirect::to('ServiceStatus')->with('error', 'Backend API is down, please try again later!');			
			}
			
		 }	
		 else if(!empty($obj) && $obj->status == 'error')
		 {
			 // There was a problem deleting the user
			Log::error('Request to check status of account failed :' . $obj2->fail_code . ':' . $obj2->fail_message);
			Log::error('Log :' . implode(' ', $obj2->job_log));
            return Redirect::to('account')->with('error', $obj->fail_message );
		 }	
		 else
		 {
			return Redirect::to('ServiceStatus')->with('error', 'Backend API is down, please try again later!');			
		 }	
	}

	public function getLogs($id)
	{
		$this->check();
		$account = CloudAccount::where('user_id', Auth::id())->find($id);
		
		if(!empty($account) && isset($account->job_id))
		{
			$responseJson = AWSBillingEngine::authenticate(array('username' => Auth::user()->username, 'password' => md5(Auth::user()->engine_key)));
		 	EngineLog::logIt(array('user_id' => Auth::id(), 'method' => 'authenticate', 'return' => $responseJson));
		 	$obj = json_decode($responseJson);
			
			if(!empty($obj) && $obj->status == 'OK')
		 	{
				$response = AWSBillingEngine::getLog(array('token' => $obj->token, 'job_id' => $account->job_id, "line_num" => 10));
				return View::make('site/account/logs', array(
            	'response' => $response,
            	'account' => $account));
				
			}
			else if(!empty($obj) && $obj->status == 'error')
			{
				Log::error('Request to Account logs failed :' . $obj2->fail_code . ':' . $obj2->fail_message);
				Log::error('Log :' . implode(' ', $obj2->job_log));
            	return Redirect::to('account')->with('error', $obj->fail_message );
			}
			else
				{
					return Redirect::to('ServiceStatus')->with('error', 'Backend API is down, please try again later!');
				}
		}
		else if(empty($account)) {
			 return Redirect::to('account')->with('info', 'No Account Logs found! ' );
		}
		else {
			 return Redirect::to('account')->with('info', 'No logs found! ' );
		}
		
	}

	public function Collection($id)
	{
		$this->check();
		$servicesConf = Config::get('aws_services');
		$serviceNames = array_keys($servicesConf);
		$account = CloudAccount::where('user_id', Auth::id())->find($id);
		$responseJson = AWSBillingEngine::authenticate(array('username' => Auth::user()->username, 'password' => md5(Auth::user()->engine_key)));
		EngineLog::logIt(array('user_id' => Auth::id(), 'method' => 'authenticate', 'return' => $responseJson));
		$obj = json_decode($responseJson);
		if(!empty($obj) && $obj->status == 'OK')
		{
			$response = AWSBillingEngine::Collection(array('token' => $obj->token, 'service_names' => $serviceNames));
			print_r($response);
		}
	}
	
	
	public function SecurityGroups($id)
	{
		$this->check();
		$account = CloudAccount::where('user_id', Auth::id())->find($id);
		
		
		return View::make('site/account/securityGroups', array(
            	'account' => $account ));
	}

	private function flatten($securityGroups)
	{
		$arr = '';
		foreach($securityGroups as $group)
		{
			$stdClass = new stdClass();
			$stdClass-> Group = $group['GroupId'] .'-'.$group['GroupName'] . '-'.$group['Description'];
			$stdClass-> IpPermissions = json_encode($group['IpPermissions']) ;
			$arr[] = $stdClass;
		}
		return $arr;
	}
	
	public function getSecurityGroupsData($id)
	{
		$this->check();
		$account = CloudAccount::where('user_id', Auth::id())->find($id);
		$securityGroups = CloudProvider::getSecurityGroups('getSecurityGroups', $id, '');
		
		$groups = $this->flatten($securityGroups);
		
		print json_encode($groups);
	}
     
	 /** 
	 *//* 
	 *//**
     * Remove the specified Account .
     *
     * @param $account
     *
     */
    public function postDelete($id) {
    		
    	CloudAccount::where('id', $id)->where('user_id', Auth::id())->delete();
        
        // Was the comment post deleted?
        $account = CloudAccount::where('user_id', Auth::id())->find($id);
        if (empty($account)) {
            // TODO needs to delete all of that user's content
            return Redirect::to('account')->with('success', 'Removed Account Successfully');
        } else {
            // There was a problem deleting the user
            return Redirect::to('account/' . $account->id . '/edit')->with('error', 'Error while deleting');
        }
    }
	
}
