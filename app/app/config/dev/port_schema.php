<?php
/**
 * Class and Function List:
 * Function list:
 * Classes list:
 */
// Schema for the CloudAccount specific fields, will be converted into JSON and used on the front-end with https://github.com/joshfire/jsonform

return array(
    'preferences[danger_ports]' => array(
            'type' => 'string',
            'title' => 'Danger Ports',
            'required' => true,
            'description' => 'Access to port 20, 21, 1433, 1434, 3306, 3389, 4333, 5432, or 5500 is unrestricted.',
            'default' => '20, 21, 1433, 1434, 3306, 3389, 4333, 5432, 5500'
        ) ,
        'preferences[warning_ports]' => array(
            'type' => 'string',
            'title' => 'Warning Ports',
            'required' => true,
            'description' => 'Yellow: Access to any other port is unrestricted.',
            'default' => '27017, 27018'
        ) ,
        'preferences[safe_ports]' => array(
            'type' => 'string',
            'title' => 'Safe Ports',
            'required' => true,
            'description' => 'Green: Access to port 80, 25, 443, or 465 is unrestricted.',
             'default' => '80, 25, 443, 465'
        ) ,
);