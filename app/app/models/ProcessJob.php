<?php

use Illuminate\Database\Eloquent\SoftDeletingTrait;
/**
* Class and Function List:
* Function list:
* - author()
* Classes list:
* - ProcessJob extends \
*/
class ProcessJob extends \Eloquent 
{
	use SoftDeletingTrait;
    protected $fillable = [];
	protected $softDelete = true;
	protected $table = 'processJobs';
<<<<<<< HEAD
	//protected $incrementing = false;
=======
	//public $incrementing = false;
	
>>>>>>> 44b8d81ca1f7db6e38b3f58248c9f818440594f3
    /**
     * Get the account's owner.
     *
     * @return User
     */
    public function owner() 
    {
        return $this->belongsTo('User', 'user_id');
    }
}
