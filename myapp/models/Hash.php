<?php namespace App;

class Hash
{
	public static function encrypt($string)
	{
		return password_hash($string, PASSWORD_DEFAULT);
	}

	public static function verifyPassword($input_password, $password)
	{
		return password_verify($input_password, $password);
	}
}