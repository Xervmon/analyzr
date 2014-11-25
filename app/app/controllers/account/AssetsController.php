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
 * - AssetsController extends BaseController
 */
class AssetsController extends BaseController {
    
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
    public function __construct(User $user) 
    {
        parent::__construct();
        $this->user = $user;
    }
	
    /**
     * Returns all the Accounts for logged in user.
     *
     * @return View
     */
    public function getSummary($id) 
    {
        $account = CloudAccountHelper::findAndDecrypt($id);
		
		$summary = CloudProvider::getSummary($account);
		
		return View::make('site/account/assets/index', array(
            'summary' => $summary
        ));
    }
	
	public function SecurityGroups($id)
	{
		UtilHelper::check();
		$account = CloudAccount::where('user_id', Auth::id())->find($id);
		
		
		return View::make('site/account/securityGroups', array(
            	'account' => $account ));
	}

	
	public function AwsInfo($id)
    {    
            UtilHelper::check();
            $account = CloudAccountHelper::findAndDecrypt($id);

			
			$responseJson = AWSBillingEngine::authenticate(array('username' => Auth::user()->username, 'password' => md5(Auth::user()->engine_key)));
			EngineLog::logIt(array('user_id' => Auth::id(), 'method' => 'authenticate', 'return' => $responseJson));
			$obj = json_decode($responseJson);

		
			if(!StringHelper::isJson($responseJson))
			{
				RedirectHelper::redirectAccount(Constants::ENGINE_CREDENTIALS_FAILURE);
			}
				
			if($obj->status == 'OK')
			{
				Log::info('Preparing the ServiceSummary for processing..');
				$credentials 	 	= json_decode($account->credentials);
				
				$data['token'] 	 	= $obj->token;
				$data['accountId'] 	= $credentials->accountId;

				$json = AWSBillingEngine::serviceSummary($data);
							
			if(StringHelper::isJson($json))
			{
				$ret = json_decode($json);
				if($ret->status == 'OK')
				{
				
					foreach ($ret->report->summary as $key => $value) {

							$regions[] = $key;
								if(empty($value->instances)) $instances[$key] = '';  else  $instances[$key] = $value->instances->state;  
								if(empty($value->subnet)) $subnets[$key]      = '';  else  $subnets[$key] = $value->subnet;  
								if(empty($value->volumes)) $volumes[$key]     = '';  else  $volumes[$key] = $value->volumes->state;  
								if(empty($value->rds)) $rds[$key]             = '';  else  $rds[$key] = $value->rds;  
								if(empty($value->key_pairs)) $key_pairs[$key] = '';  else  $key_pairs[$key] = $value->key_pairs;  
								if(empty($value->vpc)) $vpc[$key]             = '';  else  $vpc[$key] = $value->vpc;  
								if(empty($value->secgroup)) $secgroups[$key]  = '';  else  $secgroups[$key] = $value->secgroup;  
								
					}

					Log::info('ServiceSummary Generated Successfully');			
					return View::make('site/account/assets/awsInfo', array('account' => $account,
						'instances'=> $instances,'subnets'=> $subnets,'volumes'=> $volumes,
						'rds'=> $rds,'key_pairs'=> $key_pairs,'vpc'=> $vpc,'regions'=> $regions,
						'secgroups'=>$secgroups));
 				}
				else if($ret->status == 'error')
				{
					Log::error($ret->message.' '.json_encode($account));
					RedirectHelper::redirectAccount(Constants::FAILURE);
				}
			}
			else {
				Log::error('Failed to add to Services queue'.json_encode($account));
				RedirectHelper::redirectAccount(Constants::BAD_CREDENTIALS);
			}
		}
		else
			{
				RedirectHelper::redirectAccount(Constants::ENGINE_CREDENTIALS_FAILURE);
			}
          
         }

    public function instanceInfo($id)
    {
            UtilHelper::check();
            $account = CloudAccount::where('user_id', Auth::id())->find($id);
            $getInstancesAll = CloudProvider::getInstances($id);
            $arr = array();$i=0;
            if(!empty($getInstancesAll['Reservations']))
            {
                foreach($getInstancesAll['Reservations'] as $key => $value)
                {
                    $arr[$i]['InstanceId']=$value['Instances'][0]['InstanceId'];
                    $arr[$i]['KeyName']=$value['Instances'][0]['KeyName'];
                    $arr[$i]['PublicDnsName']=$value['Instances'][0]['PublicDnsName'];
                    $arr[$i]['ImageId']=$value['Instances'][0]['ImageId'];
                    $arr[$i]['LaunchTime']=$value['Instances'][0]['LaunchTime'];
                    $arr[$i]['State']=$value['Instances'][0]['State']['Name'];
                    $i++;
                }
            }   
			return View::make('site/account/assets/instanceInfo', array('account' => $account,'instanceDetails'=> $arr));
    }

    public function ebsInfo($id)
    {
            UtilHelper::check();
            $account = CloudAccount::where('user_id', Auth::id())->find($id);
            $getEBSAll = CloudProvider::getEBS($id);
            $arr = array();$i=0;
            if(!empty($getEBSAll['Volumes']))
            {
                foreach($getEBSAll['Volumes'] as $key => $value)
                {
                    $arr[$i]['VolumeId']=$value['VolumeId'];
                    $arr[$i]['SnapshotId']=$value['SnapshotId'];
                    $arr[$i]['AvailabilityZone']=$value['AvailabilityZone'];
                    $i++;
                }
            }   
			return View::make('site/account/assets/ebsInfo', array('account' => $account,'instanceDetails'=> $arr));
    }
    
    public function sgInfo($id)
    {
            UtilHelper::check();
            $account = CloudAccount::where('user_id', Auth::id())->find($id);
            $getSGAll = CloudProvider::getSG($id);
            $arr = array();$i=0;
            if(!empty($getSGAll['SecurityGroups']))
            {
                foreach($getSGAll['SecurityGroups'] as $key => $value)
                {
                    $arr[$i]['GroupId']=$value['GroupId'];
                    $arr[$i]['GroupName']=$value['GroupName'];
                    $arr[$i]['Description']=$value['Description'];
                    $i++;
                }
            }   
			// return View::make('site/account/assets/sgInfo', array('account' => $account,'instanceDetails'=> $arr));
    }
	
	
    
    public function kpInfo($id)
    {
            UtilHelper::check();
            $account = CloudAccount::where('user_id', Auth::id())->find($id);
            $getKPall = CloudProvider::getKP($id);
            $arr = array();$i=0;
            if(!empty($getKPall['KeyPairs']))
            {
                foreach($getKPall['KeyPairs'] as $key => $value)
                {
                    $arr[$i]['KeyName']=$value['KeyName'];
                    $i++;
                }
            }   
			return View::make('site/account/assets/kpInfo', array('account' => $account,'instanceDetails'=> $arr));
    }
	
	
}
