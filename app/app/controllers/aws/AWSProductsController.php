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
		$data = Config::get('ec2Products');
		
		$final = json_decode($data[0], true);
		$arr = '';
		foreach($final as $key => $value)
		{
			$value['name'] = $value['name'] .'-' .$key;
			$arr[] = $value;
		}
		
		return View::make('site/aws/ec2products', array(
            	'ec2Products' => $arr ));
		
	}
     
	
	
}
