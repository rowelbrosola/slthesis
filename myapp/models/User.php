<?php namespace App;

use Illuminate\Database\Eloquent\Model as Eloquent;
use App\Session;
use App\Redirect;

class User extends Eloquent
{
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
}
