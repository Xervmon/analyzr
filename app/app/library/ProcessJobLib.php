<?php
/**
 * Class and Function List:
 * Function list:
 * - AWSAuth()
 * - getStatus()
 * - process()
 * Classes list:
 * - ProcessJobLib
 */

class ProcessJobLib 
{
	private $id;
	
	/**
     * Inject the models.
     * @param Account $account
     * @param User $user
     */
    public function __construct() 
    {
      
    }

	public function saveJob($processJob)
	{
		$processJob->save();
	}
	
	public function process(& $account)
	{
		UtilHelper::check();
		$user = Auth::user();

		//@TODO : If user has a subscription, we can control here..
		Log::info('Processing ..' . $account->cloudProvider. '..');
		$response = $this->backgroundJob($account);
		return $response;
	}
	
	private function backgroundJob(& $account)
	{
		/*
		Step 1 - first login
		Step 2 - Then submit the job for processing
		Step 3 - Store the returns in the processjobTable.
		*/
		
		$responseJson = AWSBillingEngine::authenticate(array('username' => Auth::user()->username, 'password' => md5(Auth::user()->engine_key)));
		EngineLog::logIt(array('user_id' => Auth::id(), 'method' => 'authenticate', 'return' => $responseJson));
		$obj = WSObj::getObject($responseJson);
		
		if($obj->status == 'OK')
		{
			Log::info('Preparing the account for processing..');
			$credentials    = StringHelper::decrypt($account->credentials, md5(Auth::user()->username));
   			$credentials    = json_decode($credentials);

			$data['token'] 	 	= $obj->token;

			$data['apiKey'] 	= StringHelper::encrypt($credentials ->apiKey, md5(Auth::user()->username));
			$data['secretKey'] 	= StringHelper::encrypt($credentials ->secretKey, md5(Auth::user()->username));
			switch($account->profileType)
			{
				case Constants::READONLY_PROFILE : 	$data['accountId'] 	= $credentials->accountId;
													$data['billingBucket'] = $credentials->billingBucket;
													$json = $this->executeProcess(Constants::BILLING, $data);
													Log::info('Adding the job to '.Constants::READONLY_PROFILE.' '.Constants::BILLING.' queue for processing..'.$json);
													$pJob1 = WSObj::getObject($json);
													$this->pushToProcessJobTable($account, $data, $pJob1 , 'create_billing'); 
													$json = $this->executeProcess(Constants::SERVICES, $data);
													Log::info('Adding the job to '.Constants::READONLY_PROFILE.' '. Constants::SERVICES.' queue for processing..'.$json);
													$pJob2 = WSObj::getObject($json);
													$this->pushToProcessJobTable($account, $data, $pJob2, Constants::SERVICES);
													break;
													
				case Constants::SECURITY_PROFILE : $data['assumedRole'] = $credentials->assumedRole;
												   $data['securityToken'] = empty($credentials->securityToken) ? '' : $credentials->securityToken;
												   $json = $this->executeProcess('securityAudit', $data);
												   Log::info('Adding the job to '.Constants::READONLY_PROFILE.' securityAudit'.' queue for processing..'.$json);
												   $pJob1 = WSObj::getObject($json);
												   $this->pushToProcessJobTable($account, $data, $pJob1 , Constants::SECURITY_AUDIT); 	
												   break;
			}
		}
		else 
		{
			throw new Exception('Unexpected error: process job submission failed!');
		}
	}

	private function executeProcess($method, $data)
	{
		$response = '';
		switch($method)
		{
			case Constants::BILLING  : $response = AWSBillingEngine::create_billing($data); break;
			case Constants::SERVICES : $response = AWSBillingEngine::create_services($data); break;
			case Constants::SECURITY_AUDIT    : $response = AWSBillingEngine::create_audit($data); break;
		}
		return $response;
	}

	private function pushToProcessJobTable($account, $data, $pJob, $operation)
	{
		$processJob = new ProcessJob();
		$processJob -> operation = $operation;
		$processJob -> input = json_encode($data);
		$processJob->cloudAccountId = $account->id;
		$processJob->user_id = $account->user_id;

		if($pJob->status == 'OK')
		{
			$processJob -> output = json_encode($pJob);
			$processJob->job_id = 	$pJob-> job_id;
			$processJob->status = Lang::get('account/account.STATUS_IN_PROCESS');
		}
		elseif ($pJob->status == 'error')
		{
			$processJob -> output = '';
			$processJob->job_id = 	'';
			$processJob->status = $pJob->status;
		}
		$this->saveJob($processJob);
	}
	
	public function getStatus($account, $jobdata)
	{
		$return = '';
		$responseJson = AWSBillingEngine::authenticate(array('username' => Auth::user()->username, 'password' => md5(Auth::user()->engine_key)));
		EngineLog::logIt(array('user_id' => Auth::id(), 'method' => 'authenticate', 'return' => $responseJson));
		$obj = WSObj::getObject($responseJson);
		
		if($obj->status == 'OK')
		{
			foreach($jobdata as $row)
			{
				$depStatusJson = AWSBillingEngine::getDeploymentStatus(array('token' => $obj->token, 'job_id' => $row->job_id));
				EngineLog::logIt(array('user_id' => Auth::id(), 'method' => 'getDeploymentStatus', 'return' => $depStatusJson));
				$obj2 = WSObj::getObject($depStatusJson);
				$return[] = $this->prepareJobData($row, $obj2);
			}
			return $return;
		}
		else {
			return '';
		}
	}
	
	private function prepareJobData($row, $obj)
	{
		if($obj->status == 'OK')
		{
			$row->status = $obj->job_status;
			$row -> output = json_encode($obj -> result);
		}
		else 
		{
			$row->status = $obj->job_status;
			$row -> output = json_encode($obj->fail_code . ':' . $obj->fail_message);
		}
		$success = $row->save();
		if (!$success) 
		{
			Log::error('Error while saving Process Job Log ');
		}
		return $row;
	}
}