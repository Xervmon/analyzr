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
            'description' => 'AWS Account Id is required field'
        ) ,
        'credentials[apiKey]' => array(
            'type' => 'string',
            'title' => 'API Key',
            'required' => true,
            'description' => 'AWS Account Id is required field'
        ) ,
        'credentials[secretKey]' => array(
            'type' => 'string',
            'title' => 'Secret Key',
            'required' => true,
            'description' => 'AWS Account Id is required field'
        ) ,
        'credentials[billingBucket]' => array(
            'type' => 'string',
            'title' => 'Billing Bucket',
            'required' => true,
            'description' => 'AWS Account Id is required field'
        ) ,
    ) ,
     'Amazon AWS:Security Profile' => array(
        'credentials[apiKey]' => array(
            'type' => 'string',
            'title' => 'API Key',
            'required' => true
        ) ,
        'credentials[secretKey]' => array(
            'type' => 'string',
            'title' => 'Secret Key',
            'required' => true
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
