<?php namespace App;

use Illuminate\Database\Eloquent\Model as Eloquent;
use App\Session;
use App\Redirect;
use App\UserProfile;

class User extends Eloquent
{
	protected $fillable = array('email', 'password', 'role_id');

    public static function login($email = null, $password = null)
	{
        $user = self::where('email',$email)->first();
		if($user)
		{
			if(Hash::verifyPassword($password, $user->password))
			{
				if (!Session::exists('user_id'))
					Session::put('user_id', $user->id);
				if ($user->role_id == 1)
				{
					Redirect::to('index.php');
				}
				else
				{
					Redirect::to('index.php');
                }
			}
			else
			{
				Session::flash('error', 'Your username and password did not match');
				Redirect::to('login.php');
			}
		}
		else
		{
			Session::flash('error', 'You\'re trying to login a non-existent account.');
			Redirect::to('login.php');
		}
	}

	public static function logout() {
		Session::delete('user_id');
		Redirect::to('login.php');
	}

	public static function add($request) {
		$user = self::create([
			'role_id' => $request['role'],
		]);
		
		UserProfile::create([
			'user_id' => $user->id,
			'firstname' => $request['firstname'],
			'lastname' => $request['lastname'],
			'advisor_id' => $request['advisor'],
			'unit_id' => $request['unit'],
			'status_id' => $request['status'],
			'coding_date' => date('Y-m-d', strtotime($request['coding_date'])),
		]);

		Session::flash('success', 'Succesfully added new user.');
		Redirect::to('users.php');
	}

	public static function isLogged($name = 'user_id') {
		if(!Session::exists($name)) {
			Redirect::to('login.php');
		}
	}

	public static function updateUser($request) {
		switch ($request['action']) {
			case 'home':
				$profile = UserProfile::where('user_id', $request['user_id'])
				->update([
					'advisor_id' => $request['advisor'],
					'unit_id' => $request['unit'],
					'status_id' => $request['status'],
					'client_number' => $request['client_number'],
					'coding_date' => date('Y-m-d', strtotime($request['coding_date'])),
				]);
				break;

			case 'profile':
				$profile = UserProfile::where('user_id', $request['user_id'])
				->update([
					'firstname' => $request['firstname'],
					'lastname' => $request['lastname'],
					'dob' => date('Y-m-d', strtotime($request['clientDob'])),
				]);

				$user = User::find($request['user_id'])
				->update([
					'email' =>  $request['email']
				]);
				break;
			
			default:
				# code...
				break;
		}
		Session::flash('success', 'Succesfully updated user.');
		Redirect::to('profile.php?id='.$request['user_id']);
	}

	public function profile()
    {
        return $this->hasOne('App\UserProfile');
	}
	
	public function role()
    {
        return $this->hasOne('App\Role', 'id', 'role_id');
	}
}
