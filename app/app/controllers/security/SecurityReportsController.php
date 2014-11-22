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
 * - SecurityReportsController extends BaseController
 */
class SecurityReportsController extends BaseController {
    
	 /**
     * User Model
     * @var User
     */
    private $account;
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
    public function __construct(CloudAccount $account, User $user) {
        parent::__construct();
        $this->account = $account;
        $this->user = $user;
    }
    
	public function getAuditReport($id)
	{
		$this->account = CloudAccountHelper::findAndDecrypt($id);
		$cred = json_decode($this->account->credentials);
		$responseJson = AWSBillingEngine::authenticate(array('username' => Auth::user()->username, 'password' => md5(Auth::user()->engine_key)));
		EngineLog::logIt(array('user_id' => Auth::id(), 'method' => 'authenticate', 'return' => $responseJson));
		$obj = json_decode($responseJson);
		
		if(!StringHelper::isJson($responseJson))
		{
			return ;
		}
		if($obj->status == 'OK')
		{
			$return = AWSBillingEngine::auditReports(array('token' => $obj->token, 'accountId' => $cred->accountId));
			
			print_r($return);
		}
	}
	
}
