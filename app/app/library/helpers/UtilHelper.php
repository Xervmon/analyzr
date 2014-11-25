<?php

/**
 * Class and Function List:
 * Function list:
 * - findAndDecrypt()
 *  Function list:
 * - save()
 * Classes list:
 * - RedirectHelper
 */
class UtilHelper
{
	public static function check($json = false)
	{
			
		if(AWSBillingEngine::getServiceStatus() == 'error')
		{
			Log::error(Lang::get('account/account.awsbilling_service_down'));
			if($json)
			{
				print json_encode(array('status' => 'error', 'message' => Lang::get('account/account.awsbilling_service_down')));
				return;	
			}
			else {
				Redirect::intended('/ServiceStatus')->with('error', Lang::get('account/account.awsbilling_service_down')); 
			}
		}
	}
}