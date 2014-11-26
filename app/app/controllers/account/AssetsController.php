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
	
	public function AwsInfo($id)
	{
			$this->check();
		  	$account = CloudAccount::where('user_id', Auth::id())->find($id);
     	  	$getInstancesAll = CloudProvider::getInstances($id);
	  
			return View::make('site/account/awsInfo', array('account' => $account,'instanceDetails'=> $getInstancesAll));
	}

	public function instanceInfo($id)
	{
			$this->check();
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

     	  	
	  		return View::make('site/account/instanceInfo', array('account' => $account,'instanceDetails'=> $arr));
	}

	public function ebsInfo($id)
	{
			$this->check();
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

     	  	
	  		return View::make('site/account/ebsInfo', array('account' => $account,'instanceDetails'=> $arr));
	}
	
	public function sgInfo($id)
	{
			$this->check();
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
			return View::make('site/account/sgInfo', array('account' => $account,'instanceDetails'=> $arr));
	}
	
	public function kpInfo($id)
	{
			$this->check();
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
     	return View::make('site/account/kpInfo', array('account' => $account,'instanceDetails'=> $arr));
	}
	
	
}
