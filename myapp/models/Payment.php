<?php namespace App;

use Illuminate\Database\Eloquent\Model as Eloquent;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\UserProfile;
use App\Session;
use App\Redirect;

class Payment extends Eloquent
{
    use SoftDeletes;

    protected $dates = ['deleted_at'];
    protected $fillable = ['user_id', 'policy_id', 'amount_paid', 'unit_id', 'created_by'];

    public static function add($request) {
        $unit = UserProfile::where('user_id', $request['client'])->get();
        
        self::create([
            'user_id' => $request['client'],
            'policy_id' => $request['policy'],
            'amount_paid' => $request['amount'],
            'unit_id' => $unit[0]->unit_id,
            'created_by' => Session::get('user_id')
        ]);

        Session::flash('success', 'Succesfully added new production.');
		Redirect::to('production.php');
    }

    public function profile() {
        return $this->hasOne('App\UserProfile', 'user_id', 'user_id');
    }

    public function unit() {
        return $this->hasOne('App\Unit', 'id', 'unit_id');
    }

    public function policy() {
        return $this->hasOne('App\Policy', 'id', 'policy_id');
    }
}
