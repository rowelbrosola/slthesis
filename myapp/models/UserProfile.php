<?php namespace App;

use Illuminate\Database\Eloquent\Model as Eloquent;

class UserProfile extends Eloquent
{
    protected $fillable = array('user_id', 'firstname', 'lastname', 'advisor_id', 'unit_id', 'status_id', 'dob', 'coding_date', 'client_number', 'image_path');
}
