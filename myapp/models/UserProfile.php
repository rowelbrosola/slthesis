<?php namespace App;

use Illuminate\Database\Eloquent\Model as Eloquent;

class UserProfile extends Eloquent
{
    protected $fillable = array('user_id', 'firstname', 'lastname', 'advisor_id', 'unit_id', 'status_id', 'dob', 'coding_date', 'client_number', 'image_path');

    public function advisor()
    {
        return $this->belongsTo('App\UserProfile', 'advisor_id', 'user_id');
    }

    public function advisee()
    {
        return $this->hasMany('App\UserProfile', 'advisor_id', 'id');
    }

    public function unit()
	{
		return $this->hasOne('App\Unit', 'id', 'unit_id');
    }
    
    public function status()
    {
        return $this->hasOne('App\Status', 'id', 'status_id');
    }
}
