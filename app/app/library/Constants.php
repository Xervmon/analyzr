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

class Constants 
{
		const AWS_CLOUD                     = 'Amazon AWS';
		const RACKSPACE_CLOUD               = 'Rackspace Cloud';
		
		const HP_CLOUD                      = 'HP Cloud';
		const OPENSTACK                     = 'OpenStack';
		const DIGITAL_OCEAN                 = 'DigitalOcean';
		
		const READONLY_PROFILE              = 'ReadOnly Profile';
		const SECURITY_PROFILE              = 'Security Profile';
		const BUDGET		                = 'Budget';
		
		const SUCCESS                       = 'SUCCESS';
		const BAD_CREDENTIALS               = 'BAD_CREDENTIALS';
		const FAILURE                       = 'FAILURE' ;
		const ENGINE_FAILURE                = 'ENGINE_FAILURE' ;
		const ENGINE_CREDENTIALS_FAILURE    = 'ENGINE_CREDENTIALS_FAILURE';
		const BILLING = 'create_billing';
		const SERVICES = 'create_services';
		const SECURITY_AUDIT = 'create_audit';
		const PORT_SCANNING = 'create_secgroup';
		
		
		const describeAccountAttributes     = 'describeAccountAttributes';
		const describeAddresses             = 'describeAddresses';
		const describeAvailabilityZones     = 'describeAvailabilityZones';
		const describeCustomerGateways      = 'describeCustomerGateways';
		const describeDhcpOptions           = 'describeDhcpOptions';
		const describeImages                = 'describeImages';
		const describeInstances             = 'describeInstances';
		const describeInternetGateways      = 'describeInternetGateways';
		const describeKeyPairs              = 'describeKeyPairs';
		const describeNetworkAcls           = 'describeNetworkAcls';
		const describeNetworkInterfaces     = 'describeNetworkInterfaces';
		const  describePlacementGroups      = 'describePlacementGroups';
		const describeReservedInstances     = 'describeReservedInstances';
		const describeRouteTables           = 'describeRouteTables';
		const describeSecurityGroups        = 'describeSecurityGroups';
		const describeSnapshots             = 'describeSnapshots';
		const describeSubnets               = 'describeSubnets';
		const describeTags                  = 'describeTags';
		const describeVolumes               = 'describeVolumes'; 
		const describeVpcPeeringConnections = 'describeVpcPeeringConnections';
		const describeVpcs                  = 'describeVpcs';
		const describeVpnConnections        = 'describeVpnConnections';
		const describeVpnGateways           = 'describeVpnGateways';
    
	
	
}

	