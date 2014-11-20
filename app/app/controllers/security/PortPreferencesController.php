<?php
/**
 * Class and Function List:
 * Function list:
 * - __construct()
 * - getIndex()
 * - getCreate()
 * - postEdit()
 * - postDelete()
 * Classes list:
 * - PortPreferencesController extends BaseController
 */
class PortPreferencesController extends BaseController {
    
	 /**
     * User Model
     * @var User
     */
    private $portPreference;
    /**
     * User Model
     * @var User
     */
    protected $user;
    /**
     * Inject the models.
     * @param Account $account
     * @param User $user
     */
    public function __construct(PortPreference $portPreference, User $user) {
        parent::__construct();
        $this->portPreference = $portPreference;
        $this->user = $user;
    }
    /**
     * Returns all the Accounts for logged in user.
     *
     * @return View
     */
    public function getIndex() {
        // Get all the user's accounts
        //Auth::id() : gives the logged in userid
        $portPreferences = $this->portPreference->where('user_id', Auth::id())->orderBy('created_at', 'DESC')->paginate(10);
		
		//$accounts = CloudAccount::where('user_id', Auth::id()) -> where('profileType', Constants::READONLY_PROFILE);
		
		return View::make('site/security/portPreferences/index', array(
            'portPreferences' => $portPreferences
        ));
    }
    /**
     * Displays the form for PortPreference creation
     *
     */
    public function getCreate($id = false) {
        $mode = $id !== false ? 'edit' : 'create';
		$portPreference =  $id !== false ? PortPreference::where('user_id', Auth::id()) ->find($id) : null;
		$accounts = CloudAccount::where('user_id', Auth::id()) -> where('profileType', Constants::READONLY_PROFILE)->get();
		$portSchema = Config::get('port_schema');
        return View::make('site/security/portPreferences/create_edit', compact('mode', 'accounts', 'portPreference', 'portSchema'));
    }
    /**
     * Saves/Edits an account
     *
     */
    public function postEdit($id = false) {
    	if($id !== false)
    		$portPreference = PortPreference::where('user_id', Auth::id())->findOrFail($id);
        
        try {
            	if (empty($portPreference)) {
                $portPreference = new PortPreference;
            	} else if ($portPreference->user_id !== Auth::id()) {
                	throw new Exception('general.access_denied');
            	}
		    
	            $portPreference->project = Input::get('project');
				$preferences = Input::get('preferences');
				$this->validatePreferences($preferences, $id);
				$portPreference -> cloudAccountId = Input::get('cloudAccountId');
	            $portPreference->preferences = json_encode($preferences);
	            $portPreference->user_id = Auth::id(); // logged in user id
	            
	            $portPreference->save();
				
	            $ret = $this->processSGScan($portPreference);
	            return $this->redirect($ret);
	           
	            //Log::info('Saving the Port preferences.');
				//return Redirect::intended('security/portPreferences')->with('success', Lang::get('security/portPreferences.portPreference_updated'));
         }
        catch(Exception $e) {
            Log::error($e);
            return Redirect::to('security/portPreferences')->with('error', $e->getMessage());
        }
    }

	private function validatePreferences($preferences)
	{
		$errors = '';
		foreach($preferences as $prefernce => $ports)
		{
			$array = explode(',', $ports);
			$ret = array_filter($array, 'is_numeric') === $array;
			if(!$ret)
			{
				$errors[] = StringHelper::removeUnderscoreUCWords($prefernce) . ' should have all numeric values separated by commas';
			}
		}
	
		if(!empty($errors))
		{
			 Log::error(json_encode($errors));
           	 throw new Exception(implode('<br/>', $errors));
		}
		return;
	}
	
	private function processSGScan(& $portPreference)
	{
		$account = CloudAccountHelper::findAndDecrypt($portPreference->cloudAccountId);
		
		/*
		 *POST /create_secgroup HTTP/1.1
{
    "token": "<token>",
    "apiKey": "<api key>",
    "secretKey": "<api secret>",
    "assumedRole": "<assumedRole>",
    "securityToken": "<securityToken>",
    "accountId": "<accountId>",
    "dangerPorts": [1],
    "warningPorts": [2],
    "safePorts": [3]
} 
		 * 
		 */
		 
		 
		$responseJson = AWSBillingEngine::authenticate(array('username' => Auth::user()->username, 'password' => md5(Auth::user()->engine_key)));
		EngineLog::logIt(array('user_id' => Auth::id(), 'method' => 'authenticate - portScan', 'return' => $responseJson));
		$obj = json_decode($responseJson);
		
		if(!StringHelper::isJson($responseJson))
		{
			return Constants::ENGINE_CREDENTIALS_FAILURE;
		}
		if($obj->status == 'OK')
		{
			Log::info('Preparing the account for processing..');
			$credentials 	 	= json_decode($account->credentials);
			$data['token'] 	 	= $obj->token;
			$data['apiKey'] 	= StringHelper::encrypt($credentials ->apiKey, md5(Auth::user()->username));
			$data['secretKey'] 	= StringHelper::encrypt($credentials ->secretKey, md5(Auth::user()->username));
			$data['accountId'] 	= $credentials->accountId;
			
			$preferences = json_decode($portPreference ->preferences);
			$data['dangerPorts'] = $preferences -> dangerPorts;
			$data['warningPorts'] = $preferences -> warningPorts;
			$data['safePorts'] = $preferences -> safePorts;
			
			$json = AWSBillingEngine::create_secgroup($data);
			
			Log::info('Adding the job to port Pref queue for processing..'.$json);
			
			if(StringHelper::isJson($json))
			{
				$ret = json_decode($json);
				if($ret->status == 'OK')
				{
					$portPreference ->status = Lang::get('account/account.STATUS_IN_PROCESS');
					$portPreference->job_id = $ret->job_id;
					$portPreference->save();
					Log::info('Job Id:'.$ret->job_id);
					return Constants::SUCCESS;
				}
				else if($ret->status == 'error')
				{
					$portPreference ->status = $ret->status;
					$portPreference->job_id = '';
					$portPreference->save();
					Log::error($ret->message.' '.json_encode($account));
					return Constants::FAILURE;
				}
			}
			else {
				Log::error('Failed to add to billing queue'.json_encode($account));
				return Constants::BAD_CREDENTIALS;
			}
		}
		else
			{
				return Constants::ENGINE_CREDENTIALS_FAILURE;
			}
		
	}

	private function redirect($state)
	{
		$ret = '';
		switch ($state)
		{
			case Constants::SUCCESS: $ret = Redirect::intended('security/portPreferences')->with('success', Lang::get('security/portPreferences.portPreference_updated')); break;
			case Constants::BAD_CREDENTIALS:
			case Constants::FAILURE : $ret = Redirect::to('security/portPreferences')->with('error', 'Check Account Credentials!'); break;
			case Constants::ENGINE_FAILURE : $ret =  Redirect::to('security/portPreferences')->with('error', 'Check if AWS Usage Processing engine is up!'); break;
			case Constants::ENGINE_CREDENTIALS_FAILURE : $ret =  Redirect::to('security/portPreferences')->with('error', 'Engine credentials mis-match. Contact support team.'); break;
		}	
		return $ret;
	}

	 /** 
	 * 
	 * Remove the specified Account .
     *
     * @param $portPreference
     *
     */
    public function postDelete($id) {
    		
    	PortPreference::where('id', $id)->where('user_id', Auth::id())->delete();
        
        // Was the comment post deleted?
        $portPreference = PortPreference::where('user_id', Auth::id())->find($id);
        if (empty($portPreference)) {
            // TODO needs to delete all of that user's content
            return Redirect::to('security/portPreferences')->with('success', 'Removed Port preference Successfully');
        } else {
            // There was a problem deleting the user
            return Redirect::to('security/portPreferences/' . $portPreference->id . '/edit')->with('error', 'Error while deleting');
        }
    }

    private function check($json = false)
	{
		if($json)
		{
			if(AWSBillingEngine::getServiceStatus() == 'error')
			{
				Log::error(Lang::get('account/account.awsbilling_service_down'));
				print json_encode(array('status' => 'error', 'message' => Lang::get('account/account.awsbilling_service_down')));
				return;
			}
		}
		else 
		{
			
			if(AWSBillingEngine::getServiceStatus() == 'error')
			{
				Log::error(Lang::get('account/account.awsbilling_service_down'));
				print json_encode(array('status' => 'error', 'message' => Lang::get('account/account.awsbilling_service_down')));
				return;
			}
		}
	}

    public function checkStatus($id)
	{
		$this->check();
		$portPreference = PortPreference::where('user_id', Auth::id())->find($id);



		if(empty($portPreference))
		{
			return Redirect::to('security/portPreferences')->with('info', 'Selected portPreference do not need refresh!');
		}
		$user = Auth::user();

		$responseJson = AWSBillingEngine::authenticate(array('username' => $user->username, 'password' => md5($user->engine_key)));
		EngineLog::logIt(array('user_id' => Auth::id(), 'method' => 'authenticate', 'return' => $responseJson));
		$obj = json_decode($responseJson);

		if(!empty($obj) && $obj->status == 'OK')
		{
			$responseJson = AWSBillingEngine::getDeploymentStatus(array('token' => $obj->token, 'job_id' => $portPreference->job_id));
			EngineLog::logIt(array('user_id' => Auth::id(), 'method' => 'getDeploymentStatus', 'return' => $responseJson));
		
			$obj2 = json_decode($responseJson);

			if(!empty($obj2) && $obj2->status == 'OK')
			{
				if(!isset($obj2 -> result))
				{
					Log::error('No Result in the checkStatus Request to be saved!');
					$obj2 -> result ='';
				} 
				
				$portPreference->status = $obj2->job_status;
				$portPreference -> wsResults = json_encode($obj2 -> result);
				$success = $portPreference->save();
				
		        if (!$success) {
		        	Log::error('Error while saving portPreference Log : '.json_encode( $portPreference->errors()));
					return Redirect::to('security/portPreferences')->with('error', 'Error saving portPreference!' );
		        }
				return Redirect::to('security/portPreferences')->with('success', $portPreference->name .' is refreshed' );
			}
			else  if(!empty($obj2) && $obj2->status == 'error')
			 {
			 	$portPreference->status = $obj2->job_status;
				$portPreference -> wsResults = json_encode($obj2 -> result);
				$success = $portPreference->save();
				
				if (!$success) {
		        	Log::error('Error while saving portPreference : '.json_encode( $portPreference->errors()));
					return Redirect::to('security/portPreferences')->with('error', 'Error saving portPreference!' );
		        }
				 // There was a problem deleting the user
				Log::error($responseJson);
				Log::error('Request to check status of portPreference failed :' . $obj2->fail_code . ':' . $obj2->fail_message);
				return Redirect::to('security/portPreferences')->with('error', 'Error while checking for status of portPreference' );
			 }	
			else
			{
				  return Redirect::to('ServiceStatus')->with('error', 'Backend API is down, please try again later!');			
			}
			
		 }	
		 else if(!empty($obj) && $obj->status == 'error')
		 {
			Log::error('Request to check status of portPreference failed :' . $obj->fail_code . ':' . $obj->fail_message);
			return Redirect::to('security/portPreferences')->with('error', $obj->fail_message );
		 }	
		 else
		 {
			return Redirect::to('ServiceStatus')->with('error', 'Backend API is down, please try again later!');			
		 }	
	}


	public function portInfo($id)
	{
			$this->check();
		  	$account = CloudAccountHelper::findAndDecrypt($id);

		  	$responseJson = AWSBillingEngine::authenticate(array('username' => Auth::user()->username, 'password' => md5(Auth::user()->engine_key)));
			EngineLog::logIt(array('user_id' => Auth::id(), 'method' => 'authenticate - portScan', 'return' => $responseJson));
			$obj = json_decode($responseJson);

			if($obj->status == 'OK')
			{
				Log::info('Preparing the Port Info for processing..');
				$credentials 	 	= json_decode($account->credentials);
				
				$data['token'] 	 	= $obj->token;
				$data['accountId'] 	= $credentials->accountId;
				echo '<pre>';
					print_r($data);
				
				$json = AWSBillingEngine::SecgroupReport($data);
				Log::info('Adding the job to port Pref queue for processing..'.$json);
				$ret = json_decode($json);

				echo '<pre>';
					print_r($ret);
					die();
			if(StringHelper::isJson($json))
			{
				$ret = json_decode($json);
				if($ret->status == 'OK')
				{
					echo '<pre>';
					print_r($ret);
					die();
					return View::make('site/security/portPreferences/portInfo', array('account' => $account,'instanceDetails'=> $getInstancesAll));
			    }
				else if($ret->status == 'error')
				{
					echo $ret->status;
					die();
				}
			}
			else {
				Log::error('Failed to add to billing queue'.json_encode($account));
				return Constants::BAD_CREDENTIALS;
			}
		}
		else
			{
				return Constants::ENGINE_CREDENTIALS_FAILURE;
			}	
		
	}
	
}
