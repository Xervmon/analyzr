<?php
/**
 * Class and Function List:
 * Function list:
 * - __construct()
 * - getIndex()
 * Classes list:
 * - HomeController extends BaseController
 */
class HomeController extends BaseController {
    /**
     * Post Model
     * @var Post
     */
     protected $account;
    /**
     * User Model
     * @var User
     */
    protected $user;
    /**
     * Inject the models.
     * @param Post $post
     * @param User $user
     */
    public function __construct(CloudAccount $account, User $user) {
        parent::__construct();
        $this->account = $account;
        $this->user = $user;
    }
    /**
     * Returns all the blog posts.
     *
     * @return View
     */

        public function getIndex() 
        {
        	if (Auth::check()) 
        	{
            	$chartDataForAccounts = CloudAccountHelper::getAccountSummary();
        	} 
        	else 
        	{
        		$chartDataForAccounts = array();
        	}

              
        $chartDataForAccounts['titleText']    = Lang::get('home/home.titleText');        
        $chartDataForAccounts['subtitleText'] = Lang::get('home/home.subtitleText');        
        $chartDataForAccounts['yAxisTitle']   = Lang::get('home/home.yAxisTitle');
        $chartDataForAccounts['account']      = Lang::get('home/home.account');

        // Show the page
        return View::make('site/home/index', array(
            'chartDataForAccounts' => $chartDataForAccounts
        ));
    }

}

