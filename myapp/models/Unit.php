<?php namespace App;

use Illuminate\Database\Eloquent\Model as Eloquent;
use App\Session;
use App\Redirect;

class Unit extends Eloquent
{
    protected $table = 'unit';
    protected $fillable = ['name', 'owner_id', 'created_by'];

    public static function add($post) {
        Unit::create([
            'name' => $post['unit'],
            'created_by' => Session::get('user_id')
        ]);

        Session::flash('success', 'Successfully added new unit');
        Redirect::to('units.php');
    }

    public function creator() {
        return $this->hasOne('App\UserProfile', 'user_id', 'created_by');
    }

    public function owner() {
        return $this->hasOne('App\UserProfile', 'user_id', 'owner_id');
    }

    public function members() {
        return $this->hasMany('App\UserProfile');
    }

    public function production() {
        return $this->hasMany('App\Production', 'advisor_unit_id', 'id');
    }
}