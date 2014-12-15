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
    public function getIndex() 
    {
        // Get all the user's accounts
        //Auth::id() : gives the logged in userid
        $accounts = $this->accounts->where('user_id', Auth::id())->orderBy('created_at', 'DESC')->paginate(10);
		
		$data= CloudAccountHelper::getAccountStatus();
        return View::make('site/account/index',
        				 array(
				            'accounts' => $data,
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
				switch($account->profileType )
				{
					case Constants::READONLY_PROFILE : 
										UtilHelper::sendMail(Auth::user(), $account, 'site/account/email', Lang::get('account/account.account_updated'));
										return Redirect::to('account/')->with('success', Lang::get('account/account.account_updated')); break;
										
					case Constants::SECURITY_PROFILE : 
										UtilHelper::sendMail(Auth::user(), $account, 'site/account/email', Lang::get('account/account.account_security_profile_updated'));
										return Redirect::to('account/')->with('success', Lang::get('account/account.account_security_profile_updated')); break;
				}
				UtilHelper::sendMail(Auth::user(), $account, 'site/account/email', Lang::get('account/account.account_updated'));
				//return Redirect::to('account/')->with('success', Lang::get('account/account.account_updated'));
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

	public function getCost($id)
	{
		UtilHelper::check();
		$response = CloudAccountHelper::getAccountCostSummary($id);
		$obj = WSObj::getObject($response);
		echo '<pre>';
		print_r($obj);	
	}
    
	public function getChartsData($id){
    	UtilHelper::check();
		
		$account = CloudAccount::where('user_id', Auth::id())->find($id);
		$response = CloudAccountHelper::getAccountCostSummary($id);
		$costdata = json_decode($response, true);
		
	   //$getCostData = CloudAccountHelper::getChartsFormat(json_decode($response, true), $account->name);
       //$getCurrentCostData = CloudAccountHelper::getAccountSummaryById($id);
		$i=0;
		$current = '';
		$previous = '';
		foreach ($costdata['cost_data'] as $key => $value) {
			if($i == 0)
			{
				$current['timestamp'] = $key;
				$current[] = $value;
				$current['totalcost'] = array_sum($current[0]['data']);								
			}
			else
			{
				$previous['timestamp'] = $key;
				$previous[] = $value;
				$previous['totalcost'] = array_sum($previous[0]['data']);
			}
			$i++;							
		}
		
          if(!empty($current))
          {
          	$getCurrentCostData  = CloudAccountHelper::getChartsFormat($current, $account->name);
          }else{
          	Log::error(Lang::get('account/account.cost_data_empty'));
                $getCurrentCostData  = Lang::get('account/account.cost_data_empty');
          }


          if(!empty($previous))
          {
          	$getPreviousCostData = CloudAccountHelper::getChartsFormat($previous, $account->name);
          }else{
          	Log::error(Lang::get('account/account.cost_data_empty'));
                $getPreviousCostData = Lang::get('account/account.warning_cost_data_empty');
          }
        
						
						 
        $currentcostchartsdata = array(
							'titleText'  => Lang::get('account/account.currenttitleText'),
							'xAxisTitle' => Lang::get('account/account.currentxAxisTitle'),
							'yAxisTitle' => Lang::get('account/account.currentyAxisTitle'),
							'result'     => $getCurrentCostData
									  );


		$previouscostchartsdata = array(
							'titleText'  => Lang::get('account/account.titleText'),
							'xAxisTitle' => Lang::get('account/account.xAxisTitle'),
							'yAxisTitle' => Lang::get('account/account.yAxisTitle'),
							'result'     => $getPreviousCostData
						               );
		      
        return View::make('site/account/charts',
        				 array(
				            'account' => $account,
				            'previouscostchartsdata'=>$previouscostchartsdata,
				            'currentcostchartsdata' =>$currentcostchartsdata
				             ));
    }


	public function getLogs($id)
	{
		UtilHelper::check();
		$account = CloudAccount::where('user_id', Auth::id())->find($id);
		
		$jobId = Input::get('jobId');
		
		if(!empty($account))
		{
			$responseJson = AWSBillingEngine::authenticate(array('username' => Auth::user()->username, 'password' => md5(Auth::user()->engine_key)));
		 	EngineLog::logIt(array('user_id' => Auth::id(), 'method' => 'authenticate', 'return' => $responseJson));
		 	$obj = WSObj::getObject($responseJson);
			
			if($obj->status == 'OK')
		 	{
				$response = AWSBillingEngine::getLog(array('token' => $obj->token, 'job_id' => $jobId, "line_num" => 10));
				print $response;
				return;
				
			}
			else if($obj->status == 'error')
			{
				Log::error('Request to Account logs failed :' . $obj2->fail_code . ':' . $obj2->fail_message);
				Log::error('Log :' . implode(' ', $obj2->job_log));
				print json_encode(array('status' => $ob->status, 'message' => $obj2->fail_code . ':' . $obj2->fail_message));
				return;
            	//return Redirect::to('account')->with('error', $obj->fail_message );
			}
			else
				{
					print json_encode(array('status' => $ob->status, 'message' => 'Backend API is down, please try again later!'));
				}
		}
		else {
			 return Redirect::to('account')->with('info', 'No logs found! ' );
		}
		
	}

	public function Collection($id)
	{
		//UtilHelper::check();
		//$accountId = CloudAccount::where('user_id', Auth::id())->find($id);
		$account = CloudAccountHelper::findAndDecrypt($id);
		$servicesConf = Config::get('aws_services');
		return View::make('site/account/collection', array(
            	                                   'account'  => $account,
            	                                   'services' => $servicesConf 
            	                                         ));
		
	}

	public function getCollectionData($id)
	{
		$account = CloudAccountHelper::findAndDecrypt($id);
		$cred = json_decode($account->credentials);
		
        $accountId    = Input::get('AccountId');
		$startDate    = Input::get('StartDate');
		$endDate      = Input::get('EndDate');
		$productName  = Input::get('ProductName');
		
		$startrange   = strtotime($startDate);
		$endrange     = strtotime($endDate);

		$servicesConf = Config::get('aws_services');		
		$serviceName  = array_keys($servicesConf);
        
        if(empty($productName)) $serviceNames   = $serviceName; else $serviceNames   = array($productName);
		if(empty($accountId)) $accountId        = $cred->accountId;
		if(empty($startrange)) $startrange      = '';
		if(empty($endrange)) $endrange          = '';
		
		$responseJson = AWSBillingEngine::authenticate(array('username' => Auth::user()->username, 'password' => md5(Auth::user()->engine_key)));
		EngineLog::logIt(array('user_id' => Auth::id(), 'method' => 'authenticate - Collection', 'return' => $responseJson));
		$obj = WSObj::getObject($responseJson);
		
		if($obj->status == 'OK')
		{
			$response = AWSBillingEngine::Collection(array('token'         => $obj->token, 
														   'service_names' => $serviceNames, 
														   'accountId'     => $accountId,
														   'startRange'    => $startrange,
														   'endRange'      => $endrange)
												    );
			$result = WSObj::getObject($response);										
			if($result->status == 'OK')
			{
				$billingData = $result -> billing_data;

				if(!empty($billingData))
				{
					$arr = []; $tags=[]; $i=0;

					foreach($billingData as $keys => $values)
					{
						foreach( $values as $key => $value)
                		{
							$arr[$i]['UsageStartDate']  = date("d F Y ", $value->UsageStartDate);
							$arr[$i]['UsageType'] 		= $value->UsageType;
							$arr[$i]['InvoiceID'] 		= $value->InvoiceID;
							$arr[$i]['RateId']          = $value->RateId;
							$arr[$i]['ResourceId']      = $value->ResourceId;
							$arr[$i]['UsageEndDate']    = date("d F Y ", $value->UsageEndDate);
							$arr[$i]['PricingPlanId']   = $value->PricingPlanId;
							$arr[$i]['UsageQuantity']   = $value->UsageQuantity;
							$arr[$i]['BlendedRate']     = $value->BlendedRate;
							
							$in = 0;
							
							if(empty($value->tags)) 
							{
							    $tags = '';
						    }
						    else
						    {
						    	foreach ($value->tags as $key1 => $value1) 
						    	{
							    	$tags[$in] = $value1->name.'-'.$value1->value;
						    		$in++;	
								}
			    			}
						    $arr[$i]['tags']             = $tags;
							$arr[$i]['aws_filename']     = $value->aws_filename;
							$arr[$i]['AvailabilityZone'] = $value->AvailabilityZone;
							$arr[$i]['ItemDescription']  = $value->ItemDescription;
							$arr[$i]['ProductName']      = $value->ProductName;
							$arr[$i]['BlendedCost']      = $value->BlendedCost;
							$arr[$i]['UnBlendedCost']    = $value->UnBlendedCost;
							$arr[$i]['ReservedInstance'] = $value->ReservedInstance;
							$arr[$i]['is_current_month'] = $value->is_current_month;
							$arr[$i]['Operation']        = $value->Operation;
							$arr[$i]['UnBlendedRate']    = $value->UnBlendedRate;
		
                			$i++;
                		}
                		
					}
              	}
			}
		}

		print json_encode($arr);
	}
		
	
	public function getAccountSummary()
	{
		$accounts = $account = CloudAccount::where('user_id', Auth::id())->get();
		return CloudAccountHelper::getAccountSummary($accounts);
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

    	//check for portpreference

    	$portPreference = PortPreference::where('user_id', Auth::id())->where('cloudAccountId', $id)->get();

    	$obj = json_decode($portPreference->toJson());

    	if(!empty($obj))
    	{
    		foreach ($obj as $key => $value) {

				PortPreference::where('id', $value->id)->where('user_id', Auth::id())->delete();
    		}

    	}  

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

     public function getTaggedcost()
    {
    	$tag_key = Input::get('key');
    	$tag_value = Input::get('value');
    	$id = Input::get('id');
    	
    	    	   	
    	$account = CloudAccountHelper::findAndDecrypt($id);
    	$cred = json_decode($account->credentials);
    	
    	
    	$responseJson = AWSBillingEngine::authenticate(array('username' => Auth::user()->username, 'password' => md5(Auth::user()->engine_key)));
    	EngineLog::logIt(array('user_id' => Auth::id(), 'method' => 'authenticate - Collection', 'return' => $responseJson));
    	$obj = WSObj::getObject($responseJson);
    	
    	if($obj->status == 'OK')
    	{
    		$response = AWSBillingEngine::getCurrentTaggedcost(array('token' => $obj->token,'accountId' =>$cred->accountId));

    		
    		$result = WSObj::getObject($response);

    		

    		$arr = array();$i=0;
    		if(!empty($result->tagged_data->Name) || !empty($tag_value) || !empty($tag_key))
    		{

    			foreach($result->tagged_data->$tag_key as $key => $value)
    			{
    				if($key == $tag_value)
    				{
    					$arr[$i]['ServiceType'] = empty($key) ? '' : $key;
    					if(empty($value))
    					{
    						$arr[$i]['Resource'] = '';
    						$arr[$i]['ResourceCount'] = '';
    					}
    					else
    					{
    						$arr[$i]['Resource'] = key((array)$value);
    						$arr[$i]['ResourceCount'] = count($value);
    					}
    					$arr[$i]['LastUpdated'] = $result->lastUpdate;
    					$arr[$i]['Status']      = $result->status;
    		
    					$i++;
    				}	
    			}
    		}

    		 return View::make('site/account/assets/taggedcost', array(
    				'account' => $account,'taggedcost' => $arr ));
    		
    		
    		
    		
    	}
    	
    	
    	
    }
	
}
