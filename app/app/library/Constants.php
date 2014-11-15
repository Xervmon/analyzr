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
	const AWS_CLOUD 						= 'Amazon AWS';
	const RACKSPACE_CLOUD 					= 'Rackspace Cloud';
	
 	const HP_CLOUD 			  				= 'HP Cloud';
 	const OPENSTACK							= 'OpenStack';
	const DIGITAL_OCEAN						= 'DigitalOcean';
	
	const BILLING_PROFILE					= 'Billing Profile';
	const SECURITY_PROFILE					= 'Security Profile';
	
	const SUCCESS = 'SUCCESS';
	const BAD_CREDENTIALS = 'BAD_CREDENTIALS';
	const FAILURE = 'FAILURE' ;
	const ENGINE_FAILURE  = 'ENGINE_FAILURE' ;
	const ENGINE_CREDENTIALS_FAILURE='ENGINE_CREDENTIALS_FAILURE';
	
}

	