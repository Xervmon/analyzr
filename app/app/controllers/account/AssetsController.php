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
		
		return View::make('site/account/assets/index', array('summary' => $summary));
    }
	
	public function SecurityGroups($id)
	{
		UtilHelper::check();
		$account = CloudAccount::where('user_id', Auth::id())->find($id);
		return View::make('site/account/assets/securityGroups', array('account' => $account ));
	}
	
	public function getSecurityGroupsData($id)
	{
		UtilHelper::check();
		$account = CloudAccount::where('user_id', Auth::id())->find($id);
		$securityGroups = CloudProvider::getSecurityGroups('getSecurityGroups', $id, '');
		
		$groups = $this->flatten($securityGroups);
		
		print json_encode($groups);
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
	
	public function checkProcessStatus($id) {
		$account = CloudAccountHelper::findAndDecrypt($id);
		$processJobs = ProcessJob::where('user_id', Auth::id())->where('cloudAccountId', $account->id)->where('operation', Lang::get('account/account.create_services'))->orderBy('created_at', 'desc')->first();
		if ($processJobs->status == 'Completed') {
			return;
		} else {
			Log::error(Lang::get('account/account.processnotcompleted'));
			return Redirect::to('account/')->with('error', Lang::get('account/account.processnotcompleted'));
		}
	}

	
	public function AwsInfo($id)
    {    
      UtilHelper::check();
      $this->checkProcessStatus($id);
      $account = CloudAccountHelper::findAndDecrypt($id);
      $responseJson = AWSBillingEngine::authenticate(array('username' => Auth::user()->username, 'password' => md5(Auth::user()->engine_key)));
      EngineLog::logIt(array('user_id' => Auth::id(), 'method' => 'authenticate', 'return' => $responseJson));
      $obj = WSObj::getObject($responseJson);
    
      if($obj->status == 'OK')
      {
          Log::info('Preparing the ServiceSummary for processing..');
         $credentials     = json_decode($account->credentials);
         $data['token']     = $obj->token;
         $data['accountId']   = $credentials->accountId;
      
         $json = AWSBillingEngine::serviceSummary($data);
         $ret = WSObj::getObject($responseJson);        
         $ret = json_decode($json);

         if($ret->status == 'OK')
         {
              if(!empty($ret->report->summary))
              { 
                foreach ($ret->report->summary as $key => $value) 
                {
                  $regions[] = $key;
                  $instances[$key] = empty($value->instances) ? '' : $value->instances->state;
                  $subnets[$key]   = empty($value->subnet)    ? '' : $value->subnet;
                  $volumes[$key]   = empty($value->volumes)   ? '' : $value->volumes->state;
                  $rds[$key]       = empty($value->rds)       ? '' : $value->rds;
                  $key_pairs[$key] = empty($value->key_pairs) ? '' : $value->key_pairs;
                  $vpc[$key]       = empty($value->vpc)       ? '' : $value->vpc;
                  $secgroups[$key] = empty($value->secgroup)  ? '' : $value->secgroup;
                  $tags[$key]      = empty($value->tags)      ? '' : $value->tags;
                }

                Log::info('ServiceSummary Generated Successfully');     
                return View::make('site/account/assets/awsInfo', array('account' => $account,
                'instances'=> $instances,'subnets'=> $subnets,'volumes'=> $volumes,
                'rds'=> $rds,'key_pairs'=> $key_pairs,'vpc'=> $vpc,'regions'=> $regions,
                'secgroups'=>$secgroups,'tags'=>$tags));
              }
          }  
          else if($ret->status == 'error')
          {
            Log::error($ret->message.' '.json_encode($account));
            RedirectHelper::redirectAccount(Constants::FAILURE);
          }
       }
       else
       {
          RedirectHelper::redirectAccount(Constants::ENGINE_CREDENTIALS_FAILURE);
       }
           
    }


  public function startInstance()
  {
      $instance_id = Input::get('key');
      $id = Input::get('id');

      UtilHelper::check();
      $account = CloudAccount::where('user_id', Auth::id())->find($id);
      $startInstance = CloudProvider::startInstance($id,$instance_id);
      
     if(!empty($startInstance))
            return Redirect::to('assets/' . $id . '/EC2')->with('Success Instance ID:', $startInstance['StartingInstances'][0]['InstanceId'] .' is ' . $startInstance['StartingInstances'][0]['CurrentState']['Name']);
     

  }

  public function stopInstance()
  {
      $instance_id = Input::get('key');
      $id = Input::get('id');

      UtilHelper::check();
      $account = CloudAccount::where('user_id', Auth::id())->find($id);
      $stopInstance = CloudProvider::stopInstance($id,$instance_id);

      if(!empty($stopInstance))
           return Redirect::to('assets/' . $id . '/EC2')->with('Success Instance ID:', $stopInstance['StoppingInstances'][0]['InstanceId'] .' is ' . $stopInstance['StoppingInstances'][0]['CurrentState']['Name']);
         
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
                    $arr[$i]['InstanceId']    = empty($value['Instances'][0]['InstanceId']) ? '' : $value['Instances'][0]['InstanceId'];
                    $arr[$i]['KeyName']       = empty($value['Instances'][0]['KeyName']) ? '' : $value['Instances'][0]['KeyName'];
                    $arr[$i]['PublicDnsName'] = empty($value['Instances'][0]['PublicDnsName']) ? '' : $value['Instances'][0]['PublicDnsName'];
                    $arr[$i]['ImageId']       = empty($value['Instances'][0]['ImageId']) ? '' : $value['Instances'][0]['ImageId'];
                    $arr[$i]['LaunchTime']    = empty($value['Instances'][0]['LaunchTime']) ? '' : $value['Instances'][0]['LaunchTime'];

                    if(empty($value['Instances'][0]['State']['Name']))
                      $arr[$i]['State'] = '';
                    else
                    {
                      $arr[$i]['State'] = $value['Instances'][0]['State']['Name'];
                      if($value['Instances'][0]['State']['Name']=='running')
                          $arr[$i]['url']      = URL::to('assets/Stop').'?id='.$id.'&key='.$arr[$i]['InstanceId'];
                      else
                          $arr[$i]['url']     = URL::to('assets/Start').'?id='.$id.'&key='.$arr[$i]['InstanceId'];
                    }
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
                  $stdClass = new stdClass();
                    $stdClass->VolumeId         = empty($value['VolumeId']) ? '' : $value['VolumeId'];
                    $stdClass->Description      = empty($value['SnapshotId']) ? '' : 'SnapshotId : '. $value['SnapshotId'] .'<br/>'. 'CreateTime : ' .$value['CreateTime']. '<br/>';
                    $stdClass->AvailabilityZone = empty($value['AvailabilityZone']) ? '' : $value['AvailabilityZone'];

          if(!empty($value['Attachments']))
          {
            $stdClass->InstanceId = $value['Attachments'][0]['InstanceId'];
          }
                    $stdClass->State    = empty($value['State']) ? '' : $value['State'];
          
          $arr[] = $stdClass;
                }
            }   
      return View::make('site/account/assets/ebsInfo', array('account' => $account,'instanceDetails'=> $arr));
    }

  public function getTagNameValue($id)
  {
     UtilHelper::check();
         $account = CloudAccount::where('user_id', Auth::id())->find($id);
         $getTagsAll = CloudProvider::getTags($id);
            $arr = array();$i=0;
            if(!empty($getTagsAll['Tags']))
            {
                foreach($getTagsAll['Tags'] as $key => $value)
                {

                    $arr[$i]['ResourceId']   = empty($value['ResourceId']) ? '' : $value['ResourceId'];
                    $arr[$i]['ResourceType'] = empty($value['ResourceType']) ? '' : $value['ResourceType'];
                    $arr[$i]['Key']          = empty($value['Key']) ? '' : $value['Key'];
                    $arr[$i]['Value']        = empty($value['Value']) ? '' : $value['Value'];
                    $arr[$i]['url']      = URL::to('account/Taggedcost').'?id='.$id.'&key='.$arr[$i]['Key'].'&value='.$arr[$i]['Value'];
                    $i++;
                }
            }   
       return View::make('site/account/assets/tagsInfo', array('account' => $account,'tagDetails'=> $arr));
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
                     $arr[$i]['GroupId']     = empty($value['GroupId']) ? '' : $value['GroupId'];
                     $arr[$i]['GroupName']   = empty($value['GroupName']) ? '' : $value['GroupName'];
                     $arr[$i]['Description'] = empty($value['Description']) ? '' : $value['Description'];
                     
                    $i++;
                }
            }   
			 return View::make('site/account/assets/sgInfo', array('account' => $account,'instanceDetails'=> $arr));
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
                    $arr[$i]['KeyName'] = empty($value['KeyName']) ? '' : $value['KeyName'];

                    $i++;
                }
            }   
			return View::make('site/account/assets/kpInfo', array('account' => $account,'instanceDetails'=> $arr));
    }


     public function vpcsInfo($id)
    {
            UtilHelper::check();
            $account = CloudAccount::where('user_id', Auth::id())->find($id);
            $getVpcsall = CloudProvider::getVpcs($id);
            $arr = array();$i=0;
            if(!empty($getVpcsall['Vpcs']))
            {
                foreach($getVpcsall['Vpcs'] as $key => $value)
                {
                    $arr[$i]['VpcId']           = empty($value['VpcId']) ? '' : $value['VpcId'];
                    $arr[$i]['State']           = empty($value['State']) ? '' : $value['State'];
                    $arr[$i]['CidrBlock']       = empty($value['CidrBlock']) ? '' : $value['CidrBlock'];
                    $arr[$i]['DhcpOptionsId']   = empty($value['DhcpOptionsId']) ? '' : $value['DhcpOptionsId'];
                    $arr[$i]['Tags']            = empty($value['Tags']) ? '' : $value['Tags'];
                    $arr[$i]['InstanceTenancy'] = empty($value['InstanceTenancy']) ? '' : $value['InstanceTenancy'];
                    
                    
                    $i++;
                }
            }   
			return View::make('site/account/assets/vpcsInfo', array('account' => $account,'instanceDetails'=> $arr));
    }

     public function subnetsInfo($id)
    {
            UtilHelper::check();
            $account = CloudAccount::where('user_id', Auth::id())->find($id);
            $getSubnetsall = CloudProvider::getSubnets($id);
            $arr = array();$i=0;
            if(!empty($getSubnetsall['Subnets']))
            {
                foreach($getSubnetsall['Subnets'] as $key => $value)
                {

                     $arr[$i]['SubnetId']                = empty($value['SubnetId']) ? '' : $value['SubnetId'];
                     $arr[$i]['State']                   = empty($value['State']) ? '' : $value['State'];
                     $arr[$i]['VpcId']                   = empty($value['VpcId']) ? '' : $value['VpcId'];
                     $arr[$i]['CidrBlock']               = empty($value['CidrBlock']) ? '' : $value['CidrBlock'];
                     $arr[$i]['AvailableIpAddressCount'] = empty($value['AvailableIpAddressCount']) ? '' : $value['AvailableIpAddressCount'];
                     $arr[$i]['AvailabilityZone']        = empty($value['AvailabilityZone']) ? '' : $value['AvailabilityZone'];
                     $arr[$i]['Tags']                    = empty($value['Tags'][0]['Key']) ? '' : $value['Tags'][0]['Key'].'=>'.$value['Tags'][0]['Value'];
                     
                     $i++;
                }
            }   
			return View::make('site/account/assets/subnetsInfo', array('account' => $account,'instanceDetails'=> $arr));
    }

    
}
