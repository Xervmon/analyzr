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
	
	public static function save($account)
	{
		$account->credentials = StringHelper::encrypt($account->credentials, md5(Auth::user()->username));
        return $account->save();
	}
	
	public static function findCurrentCost($account)
	{
		if(!empty($account))
		{
			if($account->status == 'Completed')
			{
				$responseJson = AWSBillingEngine::authenticate(array('username' => Auth::user()->username, 'password' => md5(Auth::user()->engine_key)));
			 	EngineLog::logIt(array('user_id' => Auth::id(), 'method' => 'authenticate', 'return' => $responseJson));
			 	$obj = json_decode($responseJson);
				
				if(!empty($obj) && $obj->status == 'OK')
			 	{
					$response = AWSBillingEngine::GetCurrentCost(array('token' => $obj->token));
					
					return StringHelper::isJson($response) ? json_decode($response, true) : array('status' => 'error', 'message' => 'Invalid response from processing engine') ;
				}
				else if(!empty($obj) && $obj->status == 'error')
				{
					Log::error('Request to Account logs failed :' . $obj2->fail_code . ':' . $obj2->fail_message);
					Log::error('Log :' . implode(' ', $obj2->job_log));
	            	return array('status' => 'error', 'message' => $obj2->fail_message);
				}
				else
					{
						return array('status' => 'error', 'message' => 'Backend API is down, please try again later!');
					}
			 }
			 else
			 	{
			 		return array('status' => 'error', 'message' => 'Please wait..account in '. $account->status);
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
	
}
	