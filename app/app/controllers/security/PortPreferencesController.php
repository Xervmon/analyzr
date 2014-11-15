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
		$portPreferences =  $id !== false ? PortPreference::where('user_id', Auth::id()) ->find($id) : null;
		$portSchema = Config::get('port_schema');
        return View::make('site/security/portPreferences/create_edit', compact('mode', 'portPreferences', 'portSchema'));
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
			
            $portPreference->preferences = json_encode(Input::get('preferences'));
            $portPreference->user_id = Auth::id(); // logged in user id
            $portPreference->save();
            Log::info('Saving the Port preferences.');
			return Redirect::intended('security/portPreferences')->with('success', Lang::get('security/portPreferences.portPreference_updated'));
         }
        catch(Exception $e) {
            Log::error($e);
            return Redirect::to('security/portPreferences')->with('error', $e->getMessage());
        }
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
	
}
