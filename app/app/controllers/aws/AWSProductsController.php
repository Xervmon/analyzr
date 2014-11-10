<?php
/**
 * Class and Function List:
 * Function list:
 * - __construct()
 * - getEC2Products()
 * Classes list:
 * - AWSProductsController extends BaseController
 */
class AWSProductsController extends BaseController {
   
    /**
     * Inject the models.
     * @param Account $account
     * @param User $user
     */
    public function __construct() {
        parent::__construct();
    }
   
   public function getEC2Products()
	{
		$data = Congig::get('ec2Products');
		
		return View::make('site/aws/EC2Products', array(
            	'ec2Products' => $data ));
		
	}
     
	
	
}
