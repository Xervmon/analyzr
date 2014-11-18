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
* - AWSService
*/


class AWSService
{
	private static $connection;
	public static function get($config, $service)
	{
		$serviceSummary = '';
		try
		{
			$serviceSummary[$service] = self::getService($config, $service);
			return array('status' => 'OK', 'message'=> $serviceSummary);
		}
		catch(Exception $ex)
		{
			Log::error($ex);
			return array('status' => 'error', 'message' => 'error While getting the summar info '. $service);
		}
	}
	
	private static function getService($config, $service)
	{
		$data = '';
		switch($service)
		{
			case Constants::describeAccountAttributes : $data = self::describeAccountAttributes($config); break;
			case Constants::describeAddresses : $data = self::describeAddresses($config); break;
			case Constants::describeAvailabilityZones : $data = self::describeAvailabilityZones($config); break;
			case Constants::describeCustomerGateways : $data = self::describeCustomerGateways($config); break;
			case Constants::describeDhcpOptions : $data = self::describeDhcpOptions($config); break;
			case Constants::describeImages : $data = self::describeImages($config); break;
			case Constants::describeInstances : $data = self::describeInstances($config); break;
			case Constants::describeInternetGateways : $data = self::describeInternetGateways($config); break;
			case Constants::describeKeyPairs : $data = self::describeKeyPairs($config); break;
			case Constants::describeNetworkAcls : $data = self::describeNetworkAcls($config); break;
			case Constants::describeNetworkInterfaces : $data = self::describeNetworkInterfaces($config); break;
			case Constants::describePlacementGroups : $data = self::describePlacementGroups($config); break;
			case Constants::describeReservedInstances : $data = self::describeReservedInstances($config); break;
			
			case Constants::describeRouteTables : $data = self::describeRouteTables($config); break;
			case Constants::describeSecurityGroups : $data = self::describeSecurityGroups($config); break;
			case Constants::describeSnapshots : $data = self::describeSnapshots($config); break;
			case Constants::describeSubnets : $data = self::describeSubnets($config); break;
			case Constants::describeTags : $data = self::describeTags($config); break;
			case Constants::describeImages : $data = self::describeImages($config); break;
			case Constants::describeVolumes : $data = self::describeVolumes($config); break;
			case Constants::describeVpcPeeringConnections : $data = self::describeVpcPeeringConnections($config); break;
			case Constants::describeVpcs : $data = self::describeVpcs($config); break;
			case Constants::describeVpnConnections : $data = self::describeVpnConnections($config); break;
			case Constants::describeVpnGateways : $data = self::describeVpnGateways($config); break;
		
		}
		return $data ;
	}
	
	private static function describeAccountAttributes($config, $params=array('DryRun' => true))
	{
		self::$connection = \Aws\Ec2\Ec2Client::factory($config);
		if(empty($params))
		{
			$result = self::$connection->describeAccountAttributes();
		}
		else {
			//@TODO massage $params for filtering
			$result = self::$connection->describeAccountAttributes($params);
		}
		return $result->toArray();
	}
	
	private static function describeAddresses($config, $params=array('DryRun' => true))
	{
		self::$connection = \Aws\Ec2\Ec2Client::factory($config);
		if(empty($params))
		{
			$result = self::$connection->describeAddresses();
		}
		else {
			//@TODO massage $params for filtering
			$result = self::$connection->describeAddresses($params);
		}
		return $result->toArray();
	}
	
	private static function describeAvailabilityZones($config, $params=array('DryRun' => true))
	{
		self::$connection = \Aws\Ec2\Ec2Client::factory($config);
		if(empty($params))
		{
			$result = self::$connection->describeAvailabilityZones();
		}
		else {
			//@TODO massage $params for filtering
			$result = self::$connection->describeAvailabilityZones($params);
		}
		return $result->toArray();
	}
	
	private static function describeCustomerGateways($config, $params=array('DryRun' => true))
	{
		self::$connection = \Aws\Ec2\Ec2Client::factory($config);
		if(empty($params))
		{
			$result = self::$connection->describeCustomerGateways();
		}
		else {
			//@TODO massage $params for filtering
			$result = self::$connection->describeCustomerGateways($params);
		}
		return $result->toArray();
	}
	
	private static function describeDhcpOptions($config, $params=array('DryRun' => true))
	{
		self::$connection = \Aws\Ec2\Ec2Client::factory($config);
		if(empty($params))
		{
			$result = self::$connection->describeDhcpOptions();
		}
		else {
			//@TODO massage $params for filtering
			$result = self::$connection->describeDhcpOptions($params);
		}
		return $result->toArray();
	}
	
	private static function describeImages($config, $params=array('DryRun' => true))
	{
		self::$connection = \Aws\Ec2\Ec2Client::factory($config);
		if(empty($params))
		{
			$result = self::$connection->describeImages();
		}
		else {
			//@TODO massage $params for filtering
			$result = self::$connection->describeImages($params);
		}
		return $result->toArray();
	}
	private static function describeInstances($config, $params=array('DryRun' => true))
	{
		self::$connection = \Aws\Ec2\Ec2Client::factory($config);
		if(empty($params))
		{
			$result = self::$connection->describeInstances();
		}
		else {
			//@TODO massage $params for filtering
			$result = self::$connection->describeInstances($params);
		}
		return $result->toArray();
	}
	
	private static function describeInternetGateways($config, $params=array('DryRun' => true))
	{
		self::$connection = \Aws\Ec2\Ec2Client::factory($config);
		if(empty($params))
		{
			$result = self::$connection->describeInternetGateways();
		}
		else {
			//@TODO massage $params for filtering
			$result = self::$connection->describeInternetGateways($params);
		}
		return $result->toArray();
	}
	
	private static function describeKeyPairs($config, $params=array('DryRun' => true))
	{
		self::$connection = \Aws\Ec2\Ec2Client::factory($config);
		if(empty($params))
		{
			$result = self::$connection->describeKeyPairs();
		}
		else {
			//@TODO massage $params for filtering
			$result = self::$connection->describeKeyPairs($params);
		}
		return $result->toArray();
	}
	
	private static function describeNetworkAcls($config, $params=array('DryRun' => true))
	{
		self::$connection = \Aws\Ec2\Ec2Client::factory($config);
		if(empty($params))
		{
			$result = self::$connection->describeNetworkAcls();
		}
		else {
			//@TODO massage $params for filtering
			$result = self::$connection->describeNetworkAcls($params);
		}
		return $result->toArray();
	}
	
	private static function describeNetworkInterfaces($config, $params=array('DryRun' => true))
	{
		self::$connection = \Aws\Ec2\Ec2Client::factory($config);
		if(empty($params))
		{
			$result = self::$connection->describeNetworkInterfaces();
		}
		else {
			//@TODO massage $params for filtering
			$result = self::$connection->describeNetworkInterfaces($params);
		}
		return $result->toArray();
	}
	
	private static function describePlacementGroups($config, $params=array('DryRun' => true))
	{
		self::$connection = \Aws\Ec2\Ec2Client::factory($config);
		if(empty($params))
		{
			$result = self::$connection->describePlacementGroups();
		}
		else {
			//@TODO massage $params for filtering
			$result = self::$connection->describePlacementGroups($params);
		}
		return $result->toArray();
	}
	
	private static function describeReservedInstances($config, $params=array('DryRun' => true))
	{
		self::$connection = \Aws\Ec2\Ec2Client::factory($config);
		if(empty($params))
		{
			$result = self::$connection->describeReservedInstances();
		}
		else {
			//@TODO massage $params for filtering
			$result = self::$connection->describeReservedInstances($params);
		}
		return $result->toArray();
	}
	private static function describeRouteTables($config, $params=array('DryRun' => true))
	{
		self::$connection = \Aws\Ec2\Ec2Client::factory($config);
		if(empty($params))
		{
			$result = self::$connection->describeRouteTables();
		}
		else {
			//@TODO massage $params for filtering
			$result = self::$connection->describeRouteTables($params);
		}
		return $result->toArray();
	}
	private static function describeSecurityGroups($config, $params=array('DryRun' => true))
	{
		self::$connection = \Aws\Ec2\Ec2Client::factory($config);
		if(empty($params))
		{
			$result = self::$connection->describeSecurityGroups();
		}
		else {
			//@TODO massage $params for filtering
			$result = self::$connection->describeSecurityGroups($params);
		}
		return $result->toArray();
	}
	
	private static function describeSnapshots($config, $params=array('DryRun' => true))
	{
		self::$connection = \Aws\Ec2\Ec2Client::factory($config);
		if(empty($params))
		{
			$result = self::$connection->describeSnapshots();
		}
		else {
			//@TODO massage $params for filtering
			$result = self::$connection->describeSnapshots($params);
		}
		return $result->toArray();
	}
	
	private static function describeSubnets($config, $params=array('DryRun' => true))
	{
		self::$connection = \Aws\Ec2\Ec2Client::factory($config);
		if(empty($params))
		{
			$result = self::$connection->describeSubnets();
		}
		else {
			//@TODO massage $params for filtering
			$result = self::$connection->describeSubnets($params);
		}
		return $result->toArray();
	}
	
	private static function describeTags($config, $params=array('DryRun' => true))
	{
		self::$connection = \Aws\Ec2\Ec2Client::factory($config);
		if(empty($params))
		{
			$result = self::$connection->describeTags();
		}
		else {
			//@TODO massage $params for filtering
			$result = self::$connection->describeTags($params);
		}
		return $result->toArray();
	}
	
	private static function describeVolumes($config, $params=array('DryRun' => true))
	{
		self::$connection = \Aws\Ec2\Ec2Client::factory($config);
		if(empty($params))
		{
			$result = self::$connection->describeVolumes();
		}
		else {
			//@TODO massage $params for filtering
			$result = self::$connection->describeVolumes($params);
		}
		return $result->toArray();
	}
	
	private static function describeVpcPeeringConnections($config, $params=array('DryRun' => true))
	{
		self::$connection = \Aws\Ec2\Ec2Client::factory($config);
		if(empty($params))
		{
			$result = self::$connection->describeVpcPeeringConnections();
		}
		else {
			//@TODO massage $params for filtering
			$result = self::$connection->describeVpcPeeringConnections($params);
		}
		return $result->toArray();
	}
	
	private static function describeVpcs($config, $params=array('DryRun' => true))
	{
		self::$connection = \Aws\Ec2\Ec2Client::factory($config);
		if(empty($params))
		{
			$result = self::$connection->describeVpcs();
		}
		else {
			//@TODO massage $params for filtering
			$result = self::$connection->describeVpcs($params);
		}
		return $result->toArray();
	}
	
	private static function describeVpnConnections($config, $params=array('DryRun' => true))
	{
		self::$connection = \Aws\Ec2\Ec2Client::factory($config);
		if(empty($params))
		{
			$result = self::$connection->describeVpnConnections();
		}
		else {
			//@TODO massage $params for filtering
			$result = self::$connection->describeVpnConnections($params);
		}
		return $result->toArray();
	}
	
	private static function describeVpnGateways($config, $params=array('DryRun' => true))
	{
		self::$connection = \Aws\Ec2\Ec2Client::factory($config);
		if(empty($params))
		{
			$result = self::$connection->describeVpnGateways();
		}
		else {
			//@TODO massage $params for filtering
			$result = self::$connection->describeVpnGateways($params);
		}
		return $result->toArray();
	}
} 