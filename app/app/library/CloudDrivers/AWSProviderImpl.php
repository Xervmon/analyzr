<?php
/**
* class implements IProvder and Function List:
* Interface list:
* - authenticate()
* - startInstance()
* - stopInstances()
* - restartInstances()
* - terminateInstances()
* * Classes list:
* - CloudProvider
*/


class AWSPRoviderImpl implements IProvider
{
	private $ec2Client;
	private $account;
	private $region;
	
	public function __construct($acct, $region) 
	{
	   $this->account = $acct;
	   $this->region = $region;
    }
	 
	public function authenticate() 
	{
       	return $this->init();
    }
	
	private function init() {
	 	$credentials = json_decode($this->account->credentials);
        $config['key'] = $credentials->apiKey;
        $config['secret'] = $credentials->secretKey;
        $config['region'] = $this->region;
        //empty($credentials -> instanceRegion) ? 'us-east-1' : $credentials->instanceRegion;
		$conStatus = FALSE;
        $conStatus = $this->checkCreds($config);
		return $conStatus;
    }
	
	public function startInstances($params)
	{
		if($this->init())
		{
			try
			{	
				$instanceResult = $this->ec2Client -> startInstances($params);
				if (!empty($instanceResult))
				{
					return array('status' => 'OK', 'message'  => $instanceResult-> toArray());
				} 
			}
			catch(Exception $ex)
			{
				Log::error($ex);
				return array('status' => 'error', 'message' => 'Error occured during startingInstances - '.$params['InstanceIds']);
			}
		} 
		else
		{
			Log::error(Auth::check() ? Auth::user()->username : '__Guest__');
			Log::error('startInstances Authentication failure! API key and secret key for account is not correct');
			return array('status' => 'error', 'message' => 'Authentication failure! API key and secret key for account is not correct');
		}
	}

	public function stopInstances($params)
	{
		if($this->init())
		{
			try
			{	
				$nstanceResult = $this->ec2Client -> stopInstances($params);
				if (!empty($nstanceResult))
				{
					return array('status' => 'OK', 'message'  => $nstanceResult-> toArray());
				} 
			}
			catch(Exception $ex)
			{
				Log::error($ex);
				return array('status' => 'error', 'message' => 'Error occured during stopInstances - '.$params['InstanceIds']);
			}
		} 
		else
		{
			Log::error(Auth::check() ? Auth::user()->username : '__Guest__');
			Log::error('stopInstances  Authentication failure! API key and secret key for account is not correct');
			return array('status' => 'error', 'message' => 'Authentication failure! API key and secret key for account is not correct');
		}
	}
	
	public function restartInstances($params)
	{
		if($this->init())
		{
			try
			{	
				$nstanceResult = $this->ec2Client -> restartInstances($params);
				if (!empty($nstanceResult))
				{
					return array('status' => 'OK', 'message'  => $nstanceResult-> toArray());
				} 
			}
			catch(Exception $ex)
			{
				Log::error($ex);
				return array('status' => 'error', 'message' => 'Error occured during restartInstances - '.$params['InstanceIds']);
			}
		} 
		else
		{
			Log::error(Auth::check() ? Auth::user()->username : '__Guest__');
			Log::error('restartInstances Authentication failure! API key and secret key for account is not correct');
			return array('status' => 'error', 'message' => 'Authentication failure! API key and secret key for account is not correct');
		}
	}

	public function terminateInstances($params){
		if($this->init())
		{
			try
			{	
				$nstanceResult = $this->ec2Client -> terminateInstances($params);
				if (!empty($nstanceResult))
				{
					return array('status' => 'OK', 'message'  => $nstanceResult-> toArray());
				} 
			}
			catch(Exception $ex)
			{
				Log::error($ex);
				return array('status' => 'error', 'message' => 'Error occured during terminateInstances - '.json_encode($params['InstanceIds']));
			}
		} 
		else
		{
			Log::error(Auth::check() ? Auth::user()->username : '__Guest__');
			Log::error('terminateInstances Authentication failure! API key and secret key for account is not correct');
			return array('status' => 'error', 'message' => 'Authentication failure! API key and secret key for account is not correct');
		}
	}
	
	public function describeInstances($params)
	{
		if($this->init())
		{
			try
			{	
				$nstanceResult = $this->ec2Client->DescribeInstances(array(
				'InstanceIds' => $params['InstanceIds'],
		        /*'Filters' => array(
		                		array('Name' => 'instance-id', $params['InstanceIds']),
		        			)*/
						));
				if (!empty($nstanceResult))
				{
					return array('status' => 'OK', 'message'  => $nstanceResult-> toArray());
				} 
			}
			catch(Exception $ex)
			{
				Log::error($ex);
				return array('status' => 'error', 'message' => 'Error occured during describeInstances - '.json_encode($params['InstanceIds']));
			}
		} 
		else
		{
			Log::error(Auth::check() ? Auth::user()->username : '__Guest__');
			Log::error('describeInstances Authentication failure! API key and secret key for account is not correct');
			return array('status' => 'error', 'message' => 'Authentication failure! API key and secret key for account is not correct');
		}
	}

	public function describeInstancesall($params)
	{
		if($this->init())
		{
			try
			{	
				$instanceResult = $this->ec2Client->DescribeInstances(array());
				if (!empty($instanceResult))
				{
					return array('status' => 'OK', 'message'  => $instanceResult-> toArray());
				} 
			}
			catch(Exception $ex)
			{
				Log::error($ex);
				return array('status' => 'error', 'message' => 'Error occured during describeInstances - '.json_encode($params['InstanceIds']));
			}
		} 
		else
		{
			Log::error(Auth::check() ? Auth::user()->username : '__Guest__');
			Log::error('describeInstances Authentication failure! API key and secret key for account is not correct');
			return array('status' => 'error', 'message' => 'Authentication failure! API key and secret key for account is not correct');
		}
	}

	public function describeVolumesall($params)
	{
		if($this->init())
		{
			try
			{	
				$ebsResult = $this->ec2Client->DescribeVolumes(array());
				if (!empty($ebsResult))
				{
					return array('status' => 'OK', 'message'  => $ebsResult-> toArray());
				} 
			}
			catch(Exception $ex)
			{
				Log::error($ex);
				return array('status' => 'error', 'message' => 'Error occured during describeInstances - '.json_encode($params['InstanceIds']));
			}
		} 
		else
		{
			Log::error(Auth::check() ? Auth::user()->username : '__Guest__');
			Log::error('describeInstances Authentication failure! API key and secret key for account is not correct');
			return array('status' => 'error', 'message' => 'Authentication failure! API key and secret key for account is not correct');
		}
	}

	public function describeSGall($params)
	{
		if($this->init())
		{
			try
			{	
				$sgResult = $this->ec2Client->DescribeSecurityGroups(array());
				if (!empty($sgResult))
				{
					return array('status' => 'OK', 'message'  => $sgResult-> toArray());
				} 
			}
			catch(Exception $ex)
			{
				Log::error($ex);
				return array('status' => 'error', 'message' => 'Error occured during describeInstances - '.json_encode($params['InstanceIds']));
			}
		} 
		else
		{
			Log::error(Auth::check() ? Auth::user()->username : '__Guest__');
			Log::error('describeInstances Authentication failure! API key and secret key for account is not correct');
			return array('status' => 'error', 'message' => 'Authentication failure! API key and secret key for account is not correct');
		}
	}

	public function describeKPall($params)
	{
		if($this->init())
		{
			try
			{	
				$kpResult = $this->ec2Client->DescribeKeyPairs(array());
				if (!empty($kpResult))
				{
					return array('status' => 'OK', 'message'  => $kpResult-> toArray());
				} 
			}
			catch(Exception $ex)
			{
				Log::error($ex);
				return array('status' => 'error', 'message' => 'Error occured during describeInstances - '.json_encode($params['InstanceIds']));
			}
		} 
		else
		{
			Log::error(Auth::check() ? Auth::user()->username : '__Guest__');
			Log::error('describeInstances Authentication failure! API key and secret key for account is not correct');
			return array('status' => 'error', 'message' => 'Authentication failure! API key and secret key for account is not correct');
		}
	}

	public function describeSecurityGroups($params)
	{
		if($this->init())
		{
			try
			{	
				//$securityGroups = $this->ec2Client->DescribeSecurityGroups($params);
				$securityGroups = $this -> ec2Client -> getIterator('DescribeSecurityGroups', $params);
				if (!empty($securityGroups))
				{
					return array('status' => 'OK', 'message'  => $securityGroups-> toArray());
				} 
			}
			catch(Exception $ex)
			{
				Log::error($ex);
				return array('status' => 'error', 'message' => 'Error occured during describeInstances ');
			}
		} 
		else
		{
			Log::error(Auth::check() ? Auth::user()->username : '__Guest__');
			Log::error('describeSecurityGroups Authentication failure! API key and secret key for account is not correct');
			return array('status' => 'error', 'message' => 'Authentication failure! API key and secret key for account is not correct');
		}
	}

		public function describeTags($params)
	{
		if($this->init())
		{
			try
			{	
				$tagsResult = $this->ec2Client->DescribeTags(array());
				if (!empty($tagsResult))
				{
					return array('status' => 'OK', 'message'  => $tagsResult-> toArray());
				} 
			}
			catch(Exception $ex)
			{
				Log::error($ex);
				return array('status' => 'error', 'message' => 'Error occured during describeInstances - '.json_encode($params['InstanceIds']));
			}
		} 
		else
		{
			Log::error(Auth::check() ? Auth::user()->username : '__Guest__');
			Log::error('describeInstances Authentication failure! API key and secret key for account is not correct');
			return array('status' => 'error', 'message' => 'Authentication failure! API key and secret key for account is not correct');
		}
	}

		public function describeVpcs($params)
	{
		if($this->init())
		{
			try
			{	
				$vpcsResult = $this->ec2Client->DescribeVpcs(array());
				if (!empty($vpcsResult))
				{
					return array('status' => 'OK', 'message'  => $vpcsResult-> toArray());
				} 
			}
			catch(Exception $ex)
			{
				Log::error($ex);
				return array('status' => 'error', 'message' => 'Error occured during describeInstances - '.json_encode($params['InstanceIds']));
			}
		} 
		else
		{
			Log::error(Auth::check() ? Auth::user()->username : '__Guest__');
			Log::error('describeInstances Authentication failure! API key and secret key for account is not correct');
			return array('status' => 'error', 'message' => 'Authentication failure! API key and secret key for account is not correct');
		}
	}


	public function describeSubnets($params)
	{
		if($this->init())
		{
			try
			{	
				$subnetsResult = $this->ec2Client->DescribeSubnets(array());
				if (!empty($subnetsResult))
				{
					return array('status' => 'OK', 'message'  => $subnetsResult-> toArray());
				} 
			}
			catch(Exception $ex)
			{
				Log::error($ex);
				return array('status' => 'error', 'message' => 'Error occured during describeInstances - '.json_encode($params['InstanceIds']));
			}
		} 
		else
		{
			Log::error(Auth::check() ? Auth::user()->username : '__Guest__');
			Log::error('describeInstances Authentication failure! API key and secret key for account is not correct');
			return array('status' => 'error', 'message' => 'Authentication failure! API key and secret key for account is not correct');
		}
	}
	
	public function getSummary()
	{
		$services = Config::get('services');
		$regServcies = $services [Constants::AWS_CLOUD];
		
		$regions = $regServcies['regions'];
		$services = $regServcies['services'];
		
		
		$credentials = json_decode($this->account->credentials);
        $config['key'] = $credentials->apiKey;
        $config['secret'] = $credentials->secretKey;
		$summary = '';
       	foreach($regions as $region)
		{
			$config['region'] = $region;
			
			$this->processSummary($config, $services, $summary);
		}
	}
	
	private function processSummary($config, $services, & $summary)
	{
		foreach($services as $service)
		{
			$summary[$config['region']] = $this->processService($config, $service);
		}
	}
	
	private function processService($config, $service)
	{
		$serviceSummary = '';
		if($this->checkCreds($config))
		{
			return AWSService::get($config, $service);
		}
	}

	private function checkCreds($config)
	{
		try 
        {
            $this->ec2Client = \Aws\Ec2\Ec2Client::factory($config);
			$result = $this->ec2Client->DescribeInstances(array(
		        'Filters' => array(
		                array('Name' => 'instance-type', 'Values' => array('m1.small')),
		        )
			));
		
			$reservations = $result->toArray();
			if(isset($reservations['requestId'])) $conStatus = TRUE; else $conStatus = FALSE;
        }
        catch(Exception $ex) {
            $conStatus = FALSE;
            Log::error($ex);
       }
        return $conStatus;
	}
}
