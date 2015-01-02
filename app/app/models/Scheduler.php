<?php

use Illuminate\Database\Eloquent\SoftDeletingTrait;
/**
* Class and Function List:
* Function list:
* - author()
* Classes list:
* - Scheduler extends \
*/
class Scheduler extends \Eloquent 
{
	use SoftDeletingTrait;
    protected $fillable = [];
	protected $softDelete = true;
	protected $table = 'scheduler';
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
