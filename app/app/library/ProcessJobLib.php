<?php
/**
 * Class and Function List:
 * Function list:
 * - AWSAuth()
 * - authenticate()
 * - getDriver()
 * - executeAction()
 * - getState()
 * - getSecurityGroups()
 * Classes list:
 * - CloudProvider
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
       $this->id = StringHelper::gen_uuid();
    }

	public function saveJob($processJob)
	{
		$processJob -> id = $this->id;
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
			$this->pushToProcessJobTable($account, $data, $pJob1); 
			
			$json = $this->executeProcess('services', $data);
			Log::info('Adding the job to '.'services'.' queue for processing..'.$json);
			
			$pJob2 = WSObj::getObject($json);
			$this->pushToProcessJobTable($account, $data, $pJob2);
			
		}
		else 
		{
			throw new Exception('Unexpected error: Billing process job submission failed!');
		}
	}

	private function pushToProcessJobTable($account, $data, $pJob)
	{
		$processJob = new ProcessJob();
		$processJob -> input = json_encode($data);
		$processJob->cloudAccountId = $account->id;
		$processJob->user_id = $account->user_id;
<<<<<<< HEAD


=======
		
>>>>>>> 44b8d81ca1f7db6e38b3f58248c9f818440594f3
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
				if($obj2->status == 'OK')
				{
					$jobdata->status = $obj2->job_status;
					$jobdata -> output = json_encode($obj2 -> result);
					
				}
				else {
					$jobdata->status = $obj2->job_status;
					$jobdata -> output = json_encode($obj2->fail_code . ':' . $obj2->fail_message);
				}
				
				$success = $jobdata->save();
				if (!$success) 
			    {
			    	Log::error('Error while saving Process Job Log : '.json_encode( $jobdata->errors()));
				}
				$return[] = $jobdata;
			}
			return $return;
		}
		else {
			return '';
		}
	}
	
	
}





	
