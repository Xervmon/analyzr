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
class RedirectHelper
{
	private function redirectAccount($state)
	{
		$ret = '';
		switch ($state)
		{
			case Constants::SUCCESS: $ret = Redirect::intended('account')->with('success', Lang::get('account/account.account_updated')); break;
			case Constants::BAD_CREDENTIALS:
			case Constants::FAILURE : $ret = Redirect::to('account/create')->with('error', 'Check Account Credentials!'); break;
			case Constants::ENGINE_FAILURE : $ret =  Redirect::to('account/create')->with('error', 'Check if AWS Usage Processing engine is up!'); break;
			case Constants::ENGINE_CREDENTIALS_FAILURE : $ret =  Redirect::to('account/create')->with('error', 'Engine credentials mis-match. Contact support team.'); break;
		}	
		return $ret;
	}
	

}
	