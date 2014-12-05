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

class ProcessJobLib1 
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
		$response = '';
		switch($account->profileType)
		{
			case Constants::READONLY_PROFILE  : $this->backgroundJob($account); 
												break;
			case Constants::SECURITY_PROFILE  :  $this->securityProcess($account); break;
		}
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
			$data['accountId'] 	= $credentials->accountId;
			$data['billingBucket'] = $credentials->billingBucket;

			$json = $this->executeProcess('billing', $data);
			
			Log::info('Adding the job to '.'billing'.' queue for processing..'.$json);
			
			$pJob1 = WSObj::getObject($json);
			$this->pushToProcessJobTable($account, $data, $pJob1 , 'create_billing'); 
			
			$json = $this->executeProcess('services', $data);
			Log::info('Adding the job to '.'services'.' queue for processing..'.$json);
			
			$pJob2 = WSObj::getObject($json);
			$this->pushToProcessJobTable($account, $data, $pJob2, 'create_services');
			
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
			case 'billing' : $response = AWSBillingEngine::create_billing($data); break;
			case 'services' : $response = AWSBillingEngine::create_services($data); break;
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
	
	public function getStatus2($account, $jobdata)
	{
		$return = '';
		$responseJson = AWSBillingEngine::authenticate(array('username' => Auth::user()->username, 'password' => md5(Auth::user()->engine_key)));
		EngineLog::logIt(array('user_id' => Auth::id(), 'method' => 'authenticate', 'return' => $responseJson));
		$obj = WSObj::getObject($responseJson);
		
		if($obj->status == 'OK')
		{
			$depStatusJson = AWSBillingEngine::getStatusOfAllDeployments(array('token' => $obj->token));
			EngineLog::logIt(array('user_id' => Auth::id(), 'method' => 'getStatusOfAllDeployments', 'return' => $depStatusJson));
			$obj2 = WSObj::getObject($depStatusJson);
			$return[] = $this->prepareJobData2($account, $jobdata, $obj2);
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
	
	private function prepareJobData2($account, $jobdata, $obj)
	{
		$return = '';
		$jobs = $obj->jobs;
		foreach($jobs as $job)
		{
			Log:info('Processing Job '.  $job->job_id .' for '.$account -> name);
			$processJob = $this->getJob($jobdata, $job);
			if(!empty($processJob))
			{
				if($obj->status == 'OK')
				{
					Log:debug('Processing Job '.  $job->job_id .' = '.$processJob->job_id);
					if($job->status == Lang::get('account/account.STATUS_COMPLETED'))
					{
						$processJob->status = $job->status;
						$processJob -> output = json_encode($obj -> result);
					}
					elseif($job->status == Lang::get('account/account.STATUS_FAILED')) 
					{
						$processJob->status = $job->status;
						$processJob -> output = $obj -> fail_code .':'.$obj->fail_message;
					}
				}
				else 
				{
					$processJob->status = $job->status;
					$processJob -> output = $obj -> fail_code .':'.$obj->fail_message;
				}	
				$processJob->save();
				if (!$success) 
				{
					Log::error('Error while saving Process Job Log ');
				}
				$return[] = $processJob;
			}
		}
		return $return;
	}
	
	private function getJob($jobdata, $job)
	{
		foreach($jobdata as $row)
		{
			if($row->job_id == $job->job_id)
			{
				return $job;
			}
		}
		return '';
	}

	
}