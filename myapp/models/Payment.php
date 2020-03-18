<?php namespace App;

use Illuminate\Database\Eloquent\Model as Eloquent;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\UserProfile;
use App\Session;
use App\Redirect;
use App\Production;

class Payment extends Eloquent
{
    use SoftDeletes;

    protected $dates = ['deleted_at'];
    protected $fillable = ['user_id', 'policy_id', 'amount_paid', 'unit_id', 'created_by'];

    public static function add($request) {
        $policy = Policy::find($request['policy']);
        $percentage = $policy->commission;
        $excess_premium = $policy->excess_premium;
        $today = date('Y-m-d', time());

        // get client's advisor info
        $advisor = UserProfile::where('user_id', $request['client'])->first();
        $advisor = UserProfile::where('user_id',$advisor->advisor_id)->first();
        
        self::create([
            'user_id' => $request['client'],
            'policy_id' => $request['policy'],
            'amount_paid' => $request['amount'],
            'payment_date' => $today,
            'created_by' => Session::get('user_id')
        ]);
        
        $commisssion = $request['amount'] * ($percentage / 100); // calculate commission
        if ($policy->excess_premium) {
            $commisssion += $request['amount'] * ($excess_premium / 100); // add excess premium percentage to commission
        }
        // var_dump($advisor->user_id);exit;
        Production::create([
            'user_id' => $request['client'],
            'advisor_user_id' => $advisor->user_id,
            'advisor_unit_id' => $advisor->unit_id,
            'amount' => $commisssion,
            'created_by' => Session::get('user_id')
        ]);


        Session::flash('success', 'Succesfully added new payment.');
		Redirect::to('payments.php');
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
