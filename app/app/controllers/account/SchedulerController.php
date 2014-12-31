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
 * - SchedulerController extends BaseController
 */
class SchedulerController extends BaseController {
    
    
   /** 
   *
     * CloudAccount Model
     * @var accounts
     */
    protected $accounts;
    
   /** 
   *
     * User Model
     * @var User
     */
    protected $user;
  
    /**
     * Inject the models.
     * @param Account $account
     * @param User $user
     */
    public function __construct(Scheduler $scheduler, User $user) {
        parent::__construct();
        $this->scheduler = $scheduler;
        $this->user = $user;
    }
  
    /**
     * Returns all the Accounts for logged in user.
     *
     * @return View
     */

     public function getIndex() 
    {
        // Get all the user's scheduler accounts
        //Auth::id() : gives the logged in userid
        $schedulers = $this->scheduler
                                -> where('scheduler.user_id', Auth::id())
                                -> join('cloudAccounts', 'cloudAccounts.id', '=', 'scheduler.cloudAccountId')
                                -> select('scheduler.*', 'cloudAccounts.name','cloudAccounts.cloudProvider','cloudAccounts.status','cloudAccounts.profileType')
                                -> orderBy('scheduler.created_at', 'DESC')
                                -> paginate(10);

        if(!Auth::check())
        {
            Log::error("Not authorized access");
            return Redirect::to('scheduler')->with('error', Lang::get('scheduler/scheduler.scheduler_auth_failed'));
        }
         $data = '';

        return View::make('site/account/scheduler/index', array(

                                             'schedulers' => $schedulers,
            
                                               ));
    }  

    public function getCreate($id = false) {

        $mode                = $id !== false ? 'edit' : 'create';
        $scheduler           = $id !== false ? scheduler::where('user_id', Auth::id())->findOrFail($id) : '';
        $AWSregions          = Config::get('services');
        $regions             = $AWSregions['Amazon AWS']['regions'];
        $Updatescheduler     = array('dialy', 'weekly', 'monthly', 'yearly');
    
    if($id !== false)
        {
          $accounts    = CloudAccount::where('user_id', Auth::id())->where('id', $scheduler->cloudAccountId)->where('profileType', Lang::get('security/portPreferences.readonlyProfile'))->get();
        }
    else 
        {
         $accounts    = CloudAccount::where('user_id', Auth::id())->where('profileType', Lang::get('security/portPreferences.readonlyProfile'))->get();
        }
        return View::make('site/account/scheduler/create_edit', compact('mode', 'scheduler', 'regions', 'Updatescheduler', 'accounts'));
    }


     public function getInstanceInfo($id,$region)
     {
             UtilHelper::check();

            $account = CloudAccount::where('user_id', Auth::id())->find($id);
            $getInstancesAll = CloudProvider::getInstances($id,array(),$region);

            $arr = array();$i=0;
            if(!empty($getInstancesAll['Reservations']))
            {
                foreach($getInstancesAll['Reservations'] as $key => $value)
                {
                    $arr[$i]['InstanceId']    = empty($value['Instances'][0]['InstanceId']) ? '' : $value['Instances'][0]['InstanceId'];
                    $arr[$i]['KeyName']       = empty($value['Instances'][0]['KeyName']) ? '' : $value['Instances'][0]['KeyName'];
                 
                 $i++;
                }
            }   

                 print json_encode($arr);               

     }


     public function postEdit($id = false) {

       if($id !== false)
            $scheduler = Scheduler::where('user_id', Auth::id())->findOrFail($id);
    
        try {
            if (empty($scheduler)) {
                $scheduler = new Scheduler;
            } else if ($scheduler->user_id !== Auth::id()) {
                throw new Exception('general.access_denied');
            }

            $cloudAccountId                        = Input::get('cloudAccountId');
            $scheduler->cloudAccountId             = empty($cloudAccountId) ? 0 : $cloudAccountId;
            $scheduler->service                    = Input::get('service');
            $scheduler->region                     = Input::get('region');
            $scheduler->instance                   = Input::get('instance');
            $scheduler->scheduler_starts_on        = Input::get('scheduler_starts_on');
            $scheduler->scheduler_update           = Input::get('scheduler_update');
            $scheduler->scheduler_status           = Input::get('scheduler_status');
            $scheduler->schedulerNotificationEmail = Input::get('schedulerNotificationEmail');
            $scheduler->user_id                    = Auth::id();

            $success = $scheduler->save();

             if ($success) {
                return Redirect::intended('scheduler')->with('success', Lang::get('scheduler/scheduler.scheduler_updated'));
            } else {
                return Redirect::to('scheduler')->with('error', Lang::get('scheduler/scheduler.scheduler_auth_failed'));
            }
           
        }
        catch(Exception $e) {
            Log::error($e);
            return Redirect::to('scheduler')->with('error', $e->getMessage());
        }
    }


     public function postDelete($id) {
        
        Scheduler::where('id', $id)->where('user_id', Auth::id())->delete();

        $scheduler = Scheduler::where('user_id', Auth::id())->find($id);

        if (empty($scheduler)) {

            return Redirect::to('scheduler')->with('success', 'Removed Scheduler Account Successfully');
        } else {

            return Redirect::to('scheduler')->with('error', 'Error while deleting Scheduler Account');
        }
    }


}
