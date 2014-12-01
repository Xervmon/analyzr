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

	public static function findCurrentChartsCost($account)
	{
		if(!empty($account))
		{
			return self::processCompletedState($account);
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
	
	public static function getAccountSummary()
	{
		$accounts = self::getAccountStatus();
		return self::getChartData($accounts);
	}
	
	public static function getAccountCostSummary($id)
	{
		$account = self::getBillingAccountStatusById($id);
		
		$responseJson = AWSBillingEngine::authenticate(array('username' => Auth::user()->username, 'password' => md5(Auth::user()->engine_key)));
		EngineLog::logIt(array('user_id' => Auth::id(), 'method' => 'authenticate', 'return' => $responseJson));
		$obj = WSObj::getObject($responseJson);
		if($obj->status == 'OK')
		{
			if($account->job->status == Lang::get('account/account.STATUS_COMPLETED'))
			{
				$response = AWSBillingEngine::GetCost(array('token' => $obj->token, 'accountId' => $account->id));
				return $response;
			}	
			else {
				return json_encode(array('status' => 'error', 'message' => 'Account aggregation is not ready!'));
			}
		}
		else {
			return $responseJson;
		}
	}
	
	public static function getChartData($accounts)
	{
		$xAxisCategories = '';
		$series= new stdClass();
		
		$arr = '';
		$services = Config::get('aws_services');
		foreach($accounts as $account)
		{
			$drilldownSeries = new stdClass();
			switch($account->profileType)
			{
				case Constants::READONLY_PROFILE : 
					$currentCost = self::findCurrentCost($account);
					if($currentCost['status'] == 'OK')
					{
						$series -> {$account->name .'-' .Constants::READONLY_PROFILE} = $currentCost['total'];
						$costData = $currentCost['cost_data'];
						$drilldownSeries->id = $account->name .'-' .Constants::READONLY_PROFILE;
						$drilldownSeries ->name = $account->name .'-' .Constants::READONLY_PROFILE;
						foreach($costData as $key => $value)
						{
							$shortKey = self::getShortKey($key, $services);
							$drilldownSeries ->data[] = array(0 => $shortKey, 1 => $value);
						}		
						$arr[]=  $drilldownSeries;
						unset($drilldownSeries);
					}
					break;
			}
		}
		return array('series' => $series, 'drilldownSeries' => $arr);
	}

	private static function getShortKey($key, $services)
	{
		if(array_key_exists($key, $services))
		{
			return $services[$key];
		}
		else
			{
				Log::error('Key not found in aws_services.php ' . $key);
				return $key;
			} 
		
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
			if(!empty($account->jobs))
			{	
				$account->jobs = self::operationBillingCheck($account->jobs);
				switch($account->jobs->status)
				{
					case Lang::get('account/account.STATUS_COMPLETED'): return self::processCompletedState($account); break;
					case Lang::get('account/account.STATUS_FAILED'): return array('status' => 'error', 'message' => 'Account '. $account->jobs->status .' Contact support!');	break;
					default:return array('status' => 'error', 'message' => 'Please wait..account in '. $account->jobs->status);
						break;
				}
			}	
			return self::processCompletedState($account);
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

	private static function operationBillingCheck($account)
	{
		$billing_account = '';
		foreach ($account as $acc_value )
		{
			if($acc_value->operation==Lang::get('account/account.create_billing'))
			{

				$billing_account = $acc_value;
			}
		}
		return $billing_account;
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
		
		$arr = '';
		foreach($accounts as $account)
		{
			$obj = json_decode($account->toJson());
			$processJobs = ProcessJob::where('user_id', Auth::id())->where('cloudAccountId', $obj->id) -> orderBy('created_at', 'desc') -> get();
			foreach($processJobs as $job)
			{
				$obj->jobs[] = json_decode($job->toJson());
			}
			$arr[] = $obj;
		}

		return $arr;
	}
	
	public static function getBillingAccountStatusById($id)
	{
		$account = CloudAccount::where('user_id', Auth::id())->findOrFail($id) ;
		$obj = json_decode($account->toJson());
		$processJobs = ProcessJob::where('user_id', Auth::id())->where('cloudAccountId', $obj->id) -> orderBy('created_at', 'desc') -> get();
		foreach($processJobs as $job)
		{
			if($job->operation==Lang::get('account/account.create_billing'))
			{
				$obj->job = json_decode($job->toJson());	
			}
		}
		return $obj;
	} 
	
}
	