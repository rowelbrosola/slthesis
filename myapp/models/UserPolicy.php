<?php namespace App;

use Illuminate\Database\Eloquent\Model as Eloquent;
use App\Session;
use App\PolicyBenefit;

class UserPolicy extends Eloquent
{
    protected $table = 'user_policy';
    protected $fillable = ['user_id', 'policy_id', 'policy_number', 'face_amount', 'annual_premium_amount', 'mode_of_payment', 'issue_date', 'premium_due_date', 'created_by'];

    public static function addPolicy($post) {
        UserPolicy::create([
			'user_id' => $post['profile_user_id'],
			'policy_id' => $post['policy'],
			'face_amount' => $post['face_amount'],
			'annual_premium_amount' => $post['annual_premium_amount'],
			'excess_premium_amount' => $post['excess_premium_amount'],
			'policy_number' => $post['policy_number'],
			'mode_of_payment' => $post['mode_of_payment'],
			'issue_date' => date('Y-m-d', strtotime($post['issue_date'])),
            'created_by' => Session::get('user_id')
        ]);

        foreach ($benefits as $value) {
			PolicyBenefit::create([
				'policy_id' => $post['policy'],
				'benefits_id' => $value,
				'user_id' => $post['profile_user_id']
			]);
		}

        AuditTrail::add('Added policy to a client');
        Session::flash('success', 'Succesfully added new policy for this user.');
		Redirect::to('profile.php?id='.$post['profile_user_id'].'&tab=policy');
    }

    public static function updatePolicy($request) {
        $user_policy = UserPolicy::where('policy_id', $request['profile_policy_id'])->where('user_id',  $request['profile_user_id'])->first();
        $user_policy->mode_of_payment = $request['mode_of_payment'];
        $user_policy->save();

        AuditTrail::add('Updated client\'s policy');
        Session::flash('success', 'Succesfully updated policy for this user.');
        Redirect::to('profile.php?policy_id='.$user_policy->policy_id.'&id='.$user_policy->user_id.'&tab=policy');
	}

    public function policy()
    {
        return $this->hasOne('App\Policy', 'id', 'policy_id');
    }
    
    public function benefits()
    {
        return $this->hasMany('App\PolicyBenefit', 'policy_id', 'policy_id');
    }
}
