<?php
/**
 * Class and Function List:
 * Function list:
 * Classes list:
 */
// Schema for the CloudAccount specific fields, will be converted into JSON and used on the front-end with https://github.com/joshfire/jsonform

return array(
    'Amazon AWS:Billing Profile' => array(
        'credentials[accountId]' => array(
            'type' => 'string',
            'title' => 'Account ID',
            'required' => true,
            'description' => 'Your AWS Account Id is required field. We just need set up <b>ReadOnly</b> policy for credentials'
        ) ,
        'credentials[apiKey]' => array(
            'type' => 'string',
            'title' => 'API Key',
            'required' => true,
            'description' => 'API Key that you create within AWS IAM UI. <a href="https://console.aws.amazon.com/iam/home?region=us-east-1#home"> Identity and Access Management</a>'
        ) ,
        'credentials[secretKey]' => array(
            'type' => 'string',
            'title' => 'Secret Key',
            'required' => true,
            'description' => 'Secret Key that you create within AWS IAM UI. <a href="https://console.aws.amazon.com/iam/home?region=us-east-1#home"> Identity and Access Management</a>'
        ) ,
        'credentials[billingBucket]' => array(
            'type' => 'string',
            'title' => 'Billing Bucket',
            'required' => true,
            'description' => 'The bucket configured to host aws usage data. <a href="https://console.aws.amazon.com/billing/home?#/preferences">Billing Preferences</a>'
        ) ,
    ) ,
     'Amazon AWS:Security Profile' => array(
        'credentials[apiKey]' => array(
            'type' => 'string',
            'title' => 'API Key',
            'required' => true,
            'description' => 'API Key that you create within AWS IAM UI. <a href="https://console.aws.amazon.com/iam/home?region=us-east-1#home"> Identity and Access Management</a>'
			
        ) ,
        'credentials[secretKey]' => array(
            'type' => 'string',
            'title' => 'Secret Key',
            'required' => true,
            'description' => 'Secret Key that you create within AWS IAM UI. <a href="https://console.aws.amazon.com/iam/home?region=us-east-1#home"> Identity and Access Management</a>'
        ) ,
        'credentials[assumedRole]' => array(
            'type' => 'string',
            'title' => 'Assumed Role',
            'required' => true
        ) ,
        'credentials[securityToken]' => array(
            'type' => 'string',
            'title' => 'SecurityToken',
            'required' => true
        ) ,
    ) ,
);
