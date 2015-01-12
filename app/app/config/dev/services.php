<?php
/**
 * Class and Function List:
 * Function list:
 * Classes list:
 */
// Schema for the CloudAccount specific fields, will be converted into JSON and used on the front-end with https://github.com/joshfire/jsonform

return array(
    'Amazon AWS' => array(
    					'regions'  => array(
					                'us-east-1',
					                'us-west-1',
					                'us-west-2',
					                'eu-west-1',
					                'sa-east-1',
					                'ap-northeast-1',
					                'ap-southeast-1',
					                'ap-southeast-2',
					                'cn-north-1'
					                
					    ),
    					'services' => array(
    										'describeAccountAttributes', 'describeAddresses', 'describeAvailabilityZones', 
    										'describeCustomerGateways', 'describeDhcpOptions', 'describeImages', 'describeInstances', 
    										'describeInternetGateways', 'describeKeyPairs', 'describeNetworkAcls',
    										'describeNetworkInterfaces', 'describePlacementGroups', 'describeReservedInstances',
    										'describeRouteTables', 'describeSecurityGroups', 'describeSnapshots',
    										'describeSubnets', 'describeTags', 'describeVolumes', 'describeVpcPeeringConnections',
    										'describeVpcs', 'describeVpnConnections', 'describeVpnGateways'
    
										),
						
	) ,
     
);
