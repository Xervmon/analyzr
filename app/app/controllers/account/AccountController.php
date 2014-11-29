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
	 *
     * CloudAccount Model
     * @var accounts
     */
    protected $accounts;
    
	 /** 
	 *
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
		
		$data= CloudAccountHelper::getAccountStatus();
		
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
    public function postEdit($id = false) 
    {
    	if($id !== false)
    		$account = CloudAccount::where('user_id', Auth::id())->findOrFail($id);
    
	    try {
            	if (empty($account)) {
                	$account = new CloudAccount;
            	} else if ($account->user_id !== Auth::id()) {
                	throw new Exception('general.access_denied');
            	}
		    
            $account->name = Input::get('name');
			$providerProfile = explode(':', Input::get('cloudProvider'));
            $account->cloudProvider = $providerProfile[0];
			$account->profileType = $providerProfile[1];
            $account->credentials = json_encode(Input::get('credentials'));
            $account->user_id = Auth::id(); // logged in user id
            
            $conStatus = CloudProvider::authenticate($account);
            if ($conStatus == 1) {
            	Log::info('Credentials are encrypted before saving to DB.');
				CloudAccountHelper::save($account);
				$processJobLib = new ProcessJobLib();
				$ret = $processJobLib->process($account);

				return Redirect::to('account/')->with('success', Lang::get('account/account.account_updated'));
            } else {
                return Redirect::to('account/create')->with('error', Lang::get('account/account.account_auth_failed'));
            }
        }
        catch(Exception $e) {
            Log::error($e);
            return Redirect::to('account/create')->with('error', $e->getMessage());
        }
    }

	public function checkStatus($id)
	{
		UtilHelper::check();
		$account = CloudAccount::where('user_id', Auth::id())->find($id);
		
		//most recent job data of user
		$jobData = ProcessJob::where('user_id', Auth::id())
						-> where('cloudAccountId', $id) 
						-> whereIn('status', array(Lang::get('account/account.STATUS_IN_PROCESS'), 
												  Lang::get('account/account.STATUS_STARTED')))
						-> orderBy('created_at', 'desc')
						-> get();
		
		//This is one job at a time and multiple remote calls.				
		$processJobLib = new ProcessJobLib();
		$return = $processJobLib->getStatus($account, $jobData);	
		
		//Ideal method -- all status for one user is consolidated.
		//$return = $processJobLib->getStatus2($account, $jobData);
		
		if(empty($return))
		{
			return Redirect::to('account')->with('error', $account->name . ' could not be refreshed. Try again later!' );
		}			
		else 
		{
			return Redirect::to('account')->with('success', $account->name . ' refreshed and status updated!' );
		}
		
	}
	
	public function checkStatusForAllAccounts()
	{
		UtilHelper::check(true);
		$accounts = CloudAccount::where('user_id', Auth::id());
		foreach($accounts as $account)
		{
			$jobData = ProcessJob::where('user_id', Auth::id())
						-> where('cloudAccountId', $account->id) 
						-> whereIn('status', array(Lang::get('account/account.STATUS_IN_PROCESS'), 
												  Lang::get('account/account.STATUS_STARTED')))
						-> orderBy('created_at', 'desc')
						-> get();
			$processJobLib = new ProcessJobLib();
			$return = $processJobLib->getStatus($account, $jobData);	
			if(empty($return))
			{
				print json_encode(array('status' => 'error', 'message'=> ' Accounts could not be refreshed. Try again later!'));
				return;
			}			
			else 
			{
				print json_encode(array('status' => 'error', 'message'=> ' Accounts are processed and status updated!'));
				return;
			}
		}
		
	}

	public function checkStatus2($id)
	{
		UtilHelper::check();
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
		        	Log::error('Error while saving Account Log : '.json_encode( $account->errors()));
					return Redirect::to('account')->with('error', 'Error saving Account!' );
		        }
				return Redirect::to('account')->with('success', $account->name .' is refreshed' );
			}
			else  if(!empty($obj2) && $obj2->status == 'error')
			 {
			 	$account->status = $obj2->job_status;
				$account -> wsResults = json_encode($obj2 -> result);
				$success = $account->save();
				
				if (!$success) {
		        	Log::error('Error while saving Account : '.json_encode( $account->errors()));
					return Redirect::to('account')->with('error', 'Error saving Account!' );
		        }
				 // There was a problem deleting the user
				Log::error($responseJson);
				Log::error('Request to check status of account failed :' . $obj2->fail_code . ':' . $obj2->fail_message);
				return Redirect::to('account')->with('error', 'Error while checking for status of account' );
			 }	
			else
			{
				  return Redirect::to('ServiceStatus')->with('error', 'Backend API is down, please try again later!');			
			}
			
		 }	
		 else if(!empty($obj) && $obj->status == 'error')
		 {
			Log::error('Request to check status of account failed :' . $obj->fail_code . ':' . $obj->fail_message);
			return Redirect::to('account')->with('error', $obj->fail_message );
		 }	
		 else
		 {
			return Redirect::to('ServiceStatus')->with('error', 'Backend API is down, please try again later!');			
		 }	
	}

	public function getLogs($id)
	{
		UtilHelper::check();
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
		UtilHelper::check();
		$account = CloudAccount::where('user_id', Auth::id())->find($id);
		return View::make('site/account/collection', array(
            	'account' => $account ));
		
	}

	public function getCollectionData($id)
	{
		$account = CloudAccount::where('user_id', Auth::id())->find($id);
		
		$servicesConf = Config::get('aws_services');
		$limit  = Input::get('limit');
		$offset = Input::get('offset');
		
		if(empty($limit)) $limit = 10;
		if(empty($offset)) $offset = 0;
		
		$serviceNames = array_keys($servicesConf);
		$responseJson = AWSBillingEngine::authenticate(array('username' => Auth::user()->username, 'password' => md5(Auth::user()->engine_key)));
		EngineLog::logIt(array('user_id' => Auth::id(), 'method' => 'authenticate - Collection', 'return' => $responseJson));
		$obj = json_decode($responseJson);
		if(!empty($obj) && $obj->status == 'OK')
		{
			$response = AWSBillingEngine::Collection(array('token' => $obj->token, 
														   'service_names' => $serviceNames, 
														   'limit' => $limit, 
														   'offset' => $offset)
													);
													
			$result = json_decode($response);
			if(!empty($result) && $result->status == 'OK')
			{
				$billingData = $result -> billing_data;
				echo '<pre>';
				print_r($billingData);
				foreach($billingData as $key => $value)
				{
					
				}
			}
			//print $response;
		}
	}
	
	
	private function flatten($securityGroups)
	{
		$arr = '';
		foreach($securityGroups as $group)
		{
			$stdClass = new stdClass();
			$stdClass-> Group = $group['GroupId'] .'-'.$group['GroupName'] . '-'.$group['Description'];
			$stdClass -> IPPermissions = $this->getTable($group['IpPermissions']);
			$arr[] = $stdClass;
		}
		return $arr;
	}

	private function getTable($ipPermissions)
	{
	 	$markup = '<table id="exportTableid" class="table table-striped table-bordered">';
		foreach($ipPermissions as $row)
		{
			$markup .= '<tr>';
			foreach($row as $name => $val)
			{
				$markup .= '<td>';
				if(is_array($val))
				{
					$markup .= $name .' = ' . json_encode($val);
				}
				else {
					if(in_array($val, array(22, 80)))
					{
						$markup .= UIHelper::getLabel2('danger', $name .' = ' . $val);	
					}
					else 
					{
						$markup .= UIHelper::getLabel2('OK', $name .' = ' . $val);	
					}
				}
				$markup .= '</td>';
				
			}
			$markup .= '</tr>';
		}
		return $markup .= '</table>';
	}
	
	public function getSecurityGroupsData($id)
	{
		UtilHelper::check();
		$account = CloudAccount::where('user_id', Auth::id())->find($id);
		$securityGroups = CloudProvider::getSecurityGroups('getSecurityGroups', $id, '');
		
		$groups = $this->flatten($securityGroups);
		
		print json_encode($groups);
	}
	
	public function getChartData($id)
	{
		$account = CloudAccount::where('user_id', Auth::id())->find($id);
		Log::debug('Chart data for '. $account -> name);
		// echo '<pre>';
		// print_r($account);die();
		//var accountData = '{"cost_data":{"AWS Data Transfer":0.38,"Amazon Elastic Compute Cloud":96.67,"Amazon Simple Email Service":0.01,
		//"Amazon Simple Notification Service":0,"Amazon Simple Queue Service":0,"Amazon Simple Storage Service":105.68,"Amazon SimpleDB":0,
		//"Amazon Virtual Private Cloud":20.64},"lastUpdate":1415541555,"month":"Nov 2014","status":"OK","total":223.38}';
		
		$data = CloudAccountHelper::findCurrentChartsCost($account);
        //   echo '<pre>';
		// print_r($data);die();
		/*
		 *  { 
        "label": "One",
        "value" : 29.765957771107
      	} , */ 
      if(!isset($data['cost_data']))
	  {
	  	print json_encode(array('id'=>$id,'status' => $data['status'], 'message' =>$data['message']));
	  	return;
	  }
	  
	  $costData = $data['cost_data'];
	  $arr = '';
	  foreach($costData as $key => $value)
	  {
	  	$obj['label'] = $key;
		$obj['value'] = $value;
		$arr[] = $obj;
	  }
	  
	  print json_encode (array('id'=>$id,'chart' =>$arr, 'data' => array('lastUpdated' => stringHelper::timeAgo($data['lastUpdate']), 
	  														   'total' => $data['total'], 
	  														   'month' => $data['month'])));
	  
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
    		
    	//Delete the jobs for the account
    	Log::info('Deleting the jobs for ' . $id .' for ' . Auth::user()->username.' from Process Job');	
    	ProcessJob::where('user_id', Auth::id())->where('cloudAccountId', $id)->delete();
    		
    	CloudAccount::where('id', $id)->where('user_id', Auth::id())->delete();
        Log::info('Deleting the cloud account for ' . $id .' for ' . Auth::user()->username);	
		
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
