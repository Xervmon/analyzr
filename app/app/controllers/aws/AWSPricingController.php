<?php
/**
 * Class and Function List:
 * Function list:
 * - __construct()
 * - getIndex()
 * Classes list:
 * - AWSController extends BaseController
 */
class AWSPricingController extends BaseController {
   
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
    public function __construct(User $user) {
        parent::__construct();
       $this->user = $user;
    }
    /**
     * Returns all the Accounts for logged in user.
     *
     * @return View
     */
    public function getReserved() {
        $ec2 = new EC2InstancePrices();
		//$ec2->get_ec2_ondemand_instances_prices('us-east-1', 'm1.small', 'linux')
        return View::make('site/aws/reserved', array(
            'reserved' => $ec2->get_ec2_reserved_instances_prices()
        ));
    }

	 /**
     * Returns all the Accounts for logged in user.
     *
     * @return View
     */
    public function getOndemand() {
        $ec2 = new EC2InstancePrices();
		//$ec2->get_ec2_ondemand_instances_prices('us-east-1', 'm1.small', 'linux')
        return View::make('site/aws/ondemand', array(
            'ondemand' => $ec2->get_ec2_ondemand_instances_prices()
        ));
    }
	
	
}
