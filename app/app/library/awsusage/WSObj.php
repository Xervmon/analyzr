<?php
/**
 * Class and Function List:
 * Function list:
 * - getSecurityGroups()
 * Classes list:
 * - WSObj
 */

class WSObj 
{
	public static function getObject($response)
	{
		if(StringHelper::isJson($responseJson))
		{
			return json_decode($response);
		}
		else {
			return json_decode(json_encode(array('status'=> 'error', 'message'=> 'Unexpected Error occured! Please contact support!')));
		}
	}
}
	