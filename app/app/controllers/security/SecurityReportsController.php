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
    
	public function getAuditReports($id)
	{
		$this->account = CloudAccountHelper::findAndDecrypt($id);
		$account_details = CloudAccount::where('user_id', Auth::id())->find($id);
		 
		$responseJson = AWSBillingEngine::authenticate(array('username' => Auth::user()->username, 'password' => md5(Auth::user()->engine_key)));
		EngineLog::logIt(array('user_id' => Auth::id(), 'method' => 'authenticate', 'return' => $responseJson));
		$obj = json_decode($responseJson);
		// if(!StringHelper::isJson($responseJson))
		// {
		// 	//Redirect::intended('account/'.$id.'/edit')->with($obj->status , 'Error retrieving security audit report for '.$account->name); break;

		// }
		if($obj->status == 'OK')
		{
			$return = AWSBillingEngine::auditReports(array('token' => $obj->token, 'accountId' => $this->account->id));
			EngineLog::logIt(array('user_id' => Auth::id(), 'method' => 'auditReports', 'return' => $return));
			
			$table = UIHelper::getAuditTable($this->account, $return);
				
			if(is_array($table) && isset($table['status']) && $table['status'] == 'error')
			{
				return Redirect::intended('account/'.$id.'/edit')->with('error' , $table['message'] );
			}
			else 
			{
				return View::make('site/security/audit/reports', array('account' => $account_details,'reports' =>$table ));	
			}
			
		}
		else if($obj->status == 'error')
		{
			return Redirect::intended('account/'.$id.'/edit')->with($obj->status , 'Error retrieving security audit report for '.$account->name); break;
		}
	}

	public function getAuditReport()
	{
		$id = Input::get('accountId');
		$oid = Input::get('oid');
		$this->account = CloudAccountHelper::findAndDecrypt($id);
		$responseJson = AWSBillingEngine::authenticate(array('username' => Auth::user()->username, 'password' => md5(Auth::user()->engine_key)));
		EngineLog::logIt(array('user_id' => Auth::id(), 'method' => 'authenticate', 'return' => $responseJson));
		$obj = json_decode($responseJson);
		
		// if(!StringHelper::isJson($responseJson))
		// {
		// 	//Redirect::intended('account/'.$id.'/edit')->with($obj->status , 'Error retrieving security audit report for '.$account->name); break;

		// }

		if($obj->status == 'OK')
		{
			$return = AWSBillingEngine::auditReport(array('token' => $obj->token, 'accountId' => $this->account->id, 'oid' => $oid));

			Log::info('Return:' . $return);

			$result=json_decode($return,true);
			if($result['status']=='OK'){
                       $results ='<ul style="list-style-type:none; margin-left: -8%;";>';
                       $results.='<li>User Name : '.$result['report']['username'].'</li>';
                       $results.='<li>Report Time : '.StringHelper::timeAgo($result['report']['report_time']).'</li>';
                       $results.='<li> Audit diff from previous report :[( New data : '.implode($result['report']['diff']['new']) .' ), ( Deleted data : '.implode($result['report']['diff']['old']). ') ] </li>';
                       $results.='<li> Whether there is diff : '.var_export($result['report']['changed'],true).'</li>';
                       $results.='<ul>';
                   }
			print $results;
		}else{
		print ($obj->status .', Error retrieving security audit report');
	   }
	}
	
}
