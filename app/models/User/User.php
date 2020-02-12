<?php

use Illuminate\Database\Eloquent\Model as Eloquent;

class User extends Eloquent
{
    public static function login($email = null, $password = null)
	{
        $user = self::where('email',$email)->first();
        // var_dump($user);exit;
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
				Redirect::to('index.php');
			}
		}
		else
		{
			Session::flash('error', 'You\'re trying to login a non-existent account.');
			Redirect::to('index.php');
		}
	}
}
