<?php
/**
 * Class and Function List:
 * Function list:
 * - init()
 * - request()
 * - register()
 * - authenticate()
 * - create_billing()
 * - getDeploymentStatus()
 * - Collection()
 *  * Classes list:
 * - AWSBillingEngine
 */
class AWSBillingEngine {
    private static $connection;
    private static $orchestrationParams;
  
    private static function init() 
    {
        self::$orchestrationParams = Config::get('awsusageanalyzr');
      
    }
    
    public static function request($url, $data) {
        Log::info((Auth::check() ? Auth::user()->username : json_encode($data)) . ' URL : ' . $url);
        $process = curl_init();
        curl_setopt($process, CURLOPT_URL, $url);
        curl_setopt($process, CURLOPT_RETURNTRANSFER, TRUE);
        curl_setopt($process, CURLOPT_SSL_VERIFYPEER, FALSE);
        //url-ify the data for the POST
        curl_setopt($process, CURLOPT_CUSTOMREQUEST, "POST");
        curl_setopt($process, CURLOPT_POSTFIELDS, json_encode($data));
        curl_setopt($process, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($process, CURLOPT_HTTPHEADER, array(
            'Content-Type: application/json',
            'Content-Length: ' . strlen(json_encode($data))
        ));
        $status = curl_exec($process);
        curl_close($process);
        
        return $status;
    }
    
    public static function register($data) {
        self::init();
        return self::request(self::$orchestrationParams['endpoint_ip'] . self::$orchestrationParams['register'], $data);
    }
    
    public static function authenticate($data) {
        self::init();
        return self::request(self::$orchestrationParams['endpoint_ip'] . self::$orchestrationParams['authenticate'], $data);
    }
    
    public static function create_billing($data) {
        self::init();
        return self::request(self::$orchestrationParams['endpoint_ip'] . self::$orchestrationParams['create_billing'], $data);
    }
    
    public static function getDeploymentStatus($data) {
        self::init();
        return self::request(self::$orchestrationParams['endpoint_ip'] . self::$orchestrationParams['getDeploymentStatus'] . '/' . $data['job_id'], $data);
    }
	
	 public static function getStatusOfAllDeployments($data) {
        self::init();
        return self::request(self::$orchestrationParams['endpoint_ip'] . self::$orchestrationParams['getStatusOfAllDeployments'] , $data);
    }
	
    
	public static function GetCurrentCost($data) {
        self::init();
		Log::info('URL ' . self::$orchestrationParams['endpoint_ip'] . self::$orchestrationParams['GetCurrentCost']);
        Log::info('Data '.json_encode($data));
		$ret = self::request(self::$orchestrationParams['endpoint_ip'] . self::$orchestrationParams['GetCurrentCost'] , $data);
    	Log::debug('Ret: '.$ret);
    	return $ret;
	}
	
	public static function GetCost($data) {
        self::init();
		Log::info('URL ' . self::$orchestrationParams['endpoint_ip'] . self::$orchestrationParams['GetCost']);
        Log::info('Data '.json_encode($data));
		$ret = self::request(self::$orchestrationParams['endpoint_ip'] . self::$orchestrationParams['GetCost'] , $data);
    	Log::debug('Ret: '.$ret);
    	return $ret;
	}
    
    public static function getCurrentTaggedcost($data) {
        self::init();
        Log::info('URL ' . self::$orchestrationParams['endpoint_ip'] . self::$orchestrationParams['getCurrentTaggedcost']);
        Log::info('Data '.json_encode($data));      
        return self::request(self::$orchestrationParams['endpoint_ip'] . self::$orchestrationParams['getCurrentTaggedcost'] , $data);
    }
	
	public static function Collection($data) {
        self::init();
		Log::info('URL ' . self::$orchestrationParams['endpoint_ip'] . self::$orchestrationParams['Collection']);
        Log::info('Data '.json_encode($data));
      
        return self::request(self::$orchestrationParams['endpoint_ip'] . self::$orchestrationParams['Collection'] , $data);
    }
   
	
	public static function removeUsername($data) {
        self::init();
		Log::info('Debug :' . json_encode($data));
        return self::request(self::$orchestrationParams['endpoint_ip'] . self::$orchestrationParams['removeUsername'], $data);
    }
	
	public static function create_secgroup($data) {
        self::init();
		Log::info('Debug :' . json_encode($data));
        return self::request(self::$orchestrationParams['endpoint_ip'] . self::$orchestrationParams['create_secgroup'], $data);
    }
	
	public static function SecgroupReport($data) {
        self::init();
		Log::info('Debug :' . json_encode($data));
        return self::request(self::$orchestrationParams['endpoint_ip'] . self::$orchestrationParams['SecgroupReport'], $data);
    }
	
	public static function create_audit($data) {
        self::init();
		Log::info('Debug :' . json_encode($data));
        return self::request(self::$orchestrationParams['endpoint_ip'] . self::$orchestrationParams['create_audit'], $data);
    }
	
	public static function auditReports($data) {
        self::init();
		Log::info('Debug :' . json_encode($data));
        return self::request(self::$orchestrationParams['endpoint_ip'] . self::$orchestrationParams['auditReports'], $data);
    }
	
	public static function auditReport($data) 
	{
		Log::info('Debug :' . json_encode($data));
        return self::request(self::$orchestrationParams['endpoint_ip'] 
        					. self::$orchestrationParams['auditReport'] . '/' .$data['oid'],
        					 $data);
    }
	
	
	public static function getServiceStatus()
	{
		$responseJson = self::authenticate(array('username' => Auth::user()->username, 'password' => md5(Auth::user()->engine_key)));
		$status = 'error';
		if(StringHelper::isJson($responseJson))
		{
			$obj = json_decode($responseJson);
			if(!empty($obj) && $obj->status == 'OK')
			{
				$status = 'OK';
			}
		}	
		return $status;
	}
	
	 public static function getLog($data) {
        self::init();
		Log::info('Debug' . json_encode($data));
        return self::request(self::$orchestrationParams['endpoint_ip'] . self::$orchestrationParams['getLog'] . '/' . $data['job_id'], $data);
    }

    public static function create_services($data) {
        self::init();
        return self::request(self::$orchestrationParams['endpoint_ip'] . self::$orchestrationParams['create_services'], $data);
    }


    public static function serviceSummary($data) {
        self::init();
        return self::request(self::$orchestrationParams['endpoint_ip'] . self::$orchestrationParams['serviceSummary'], $data);
    }
    
	
}
