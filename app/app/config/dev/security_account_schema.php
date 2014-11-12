<?php
/**
 * Class and Function List:
 * Function list:
 * Classes list:
 */
// Schema for the CloudAccount specific fields, will be converted into JSON and used on the front-end with https://github.com/joshfire/jsonform

return array(
    'Amazon AWS' => array(
        'credentials[apiKey]' => array(
            'type' => 'string',
            'title' => 'API Key',
            'required' => TRUE
        ) ,
        'credentials[secretKey]' => array(
            'type' => 'string',
            'title' => 'Secret Key',
            'required' => TRUE
        ) ,
        'credentials[assumedRole]' => array(
            'type' => 'string',
            'title' => 'Assumed Role',
            'required' => FALSE
        ) ,
         'credentials[securityToken]' => array(
            'type' => 'string',
            'title' => 'Security Token',
            'required' => FALSE
        ) ,
    ) ,
);