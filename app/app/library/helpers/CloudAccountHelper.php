<?php

/**
 * Class and Function List:
 * Function list:
 * - findAndDecrypt()
 *  Function list:
 * - save()
 * Classes list:
 * - CloudAccountHelper
 */
class CloudAccountHelper
{
	public static function findAndDecrypt($id)
	{
		$account = CloudAccount::where('user_id', Auth::id())->findOrFail($id) ;
		$account->credentials = StringHelper::decrypt($account->credentials, md5(Auth::user()->username));
		return $account;
	}
	
	public static function find($id)
	{
		$account = CloudAccount::where('user_id', Auth::id())->findOrFail($id) ;
		return $account;
	}
	
	public static function save($account)
	{
		$account->credentials = StringHelper::encrypt($account->credentials, md5(Auth::user()->username));
        return $account->save();
	}
	
	public static function findCurrentCost($account)
	{
		if(AWSBillingEngine::getServiceStatus() == 'error')
		{
			Log::error(Lang::get('account/account.awsbilling_service_down'));
			return array('status' => 'error', 'message' => Lang::get('account/account.awsbilling_service_down'));
		}
		if(!empty($account))
		{
			switch($account->processStatus)
			{
				case Lang::get('account/account.STATUS_COMPLETED'): return self::processCompletedState($account); break;
				case Lang::get('account/account.STATUS_FAILED'): return array('status' => 'error', 'message' => 'Account '. $account->processStatus .' Contact support!');	break;
				default:return array('status' => 'error', 'message' => 'Please wait..account in '. $account->processStatus);
						break;
			}
		}
		else if(empty($account)) 
		{
			return array('status' => 'error', 'message' => 'Empty account, no data found');
		}
		else 
		{
			return array('status' => 'error', 'message' => 'Unexpected error. Contacted support.');	
		}
	}

	private static function processCompletedState($account)
	{
		$account->credentials = StringHelper::decrypt($account->credentials, md5(Auth::user()->username));
		$cred = json_decode($account->credentials);
		
		$responseJson = AWSBillingEngine::authenticate(array('username' => Auth::user()->username, 'password' => md5(Auth::user()->engine_key)));
		EngineLog::logIt(array('user_id' => Auth::id(), 'method' => 'authenticate', 'return' => $responseJson));
		$obj = json_decode($responseJson);
				
		if(!empty($obj) && $obj->status == 'OK')
		{
			$response = AWSBillingEngine::GetCurrentCost(array('token' => $obj->token, 'accountId' => $cred->accountId));
			return StringHelper::isJson($response) ? json_decode($response, true) : array('status' => 'error', 'message' => 'Invalid response from processing engine') ;
		}
		else if(!empty($obj) && $obj->status == 'error')
		{
			Log::error('Request to Account logs failed :' . $obj->fail_code . ':' . $obj->fail_message);
			return array('status' => 'error', 'message' => $obj->fail_message);
		}
		else
		{
			return array('status' => 'error', 'message' => 'Backend API is down, please try again later!');
		}
	}

	public static function getAccountStatus()
	{
		$accounts = CloudAccount::where('user_id', Auth::id())->get() ;
		echo '<pre>';print_r(accounts); die();
		/*$arr = '';
		foreach($accounts as $account)
		{
			
			$processJobs = ProcessJobs::where('user_id', Auth::id())->where('cloudAccountId', $account->id) -> get();
			foreach($processJobs as $job)
			{
				$account->jobs[] = $job;
			}

		}*/
		/*return DB::table('cloudAccounts')
            ->join('processJobs', 'cloudAccounts.id', '=', 'processJobs.cloudAccountId')
           ->join('users', 'users.id', '=', 'cloudAccounts.user_id')
			-> where('cloudAccounts.user_id', Auth::id())
            ->select('cloudAccounts.*', 'processJobs.id as pid', 'processJobs.input',  
            		'processJobs.operation', 'processJobs.output', 'processJobs.status as processStatus')
            ->distinct()->get();*/
	}
	
}
	