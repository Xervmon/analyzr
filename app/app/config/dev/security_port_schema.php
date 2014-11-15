<?php
/**
 * Class and Function List:
 * Function list:
 * Classes list:
 */
// Schema for the CloudAccount specific fields, will be converted into JSON and used on the front-end with https://github.com/joshfire/jsonform

return array(
    'Amazon AWS' => array(
        'parameters[danger_ports]' => array(
            'type' => 'string',
            'title' => 'Account ID',
            'required' => true,
            'description' => 'AWS Account Id is required field'
        ) ,
        'credentials[apiKey]' => array(
            'type' => 'string',
            'title' => 'API Key',
            'required' => true,
            'placeholder' => 'AWS Account Id is required field'
        ) ,
        'credentials[secretKey]' => array(
            'type' => 'string',
            'title' => 'Secret Key',
            'required' => true,
            'placeholder' => 'AWS Account Id is required field'
        ) ,
        'credentials[billingBucket]' => array(
            'type' => 'string',
            'title' => 'Billing Bucket',
            'required' => true,
            'placeholder' => 'AWS Account Id is required field'
        ) ,
    ) ,
);
