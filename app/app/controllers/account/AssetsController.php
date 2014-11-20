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
 * - AssetsController extends BaseController
 */
class AssetsController extends BaseController {
    
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
    public function __construct(User $user) 
    {
        parent::__construct();
        $this->user = $user;
    }
	
    /**
     * Returns all the Accounts for logged in user.
     *
     * @return View
     */
    public function getSummary($id) 
    {
        $account = CloudAccountHelper::findAndDecrypt($id);
		
		$summary = CloudProvider::getSummary($account);
		
		return View::make('site/account/assets/index', array(
            'summary' => $summary
        ));
    }
	
}
