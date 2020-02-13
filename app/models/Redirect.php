<?php namespace models;

class Redirect
{
    public static function to($location = null)
	{
		if ($location) 
		{
			if (is_numeric($location)) 
			{
				header('HTTP/1.0 404 Not Found');
				include 'includes/erorrs/404.php';
				exit();
			}
			header('Location: ' . $location);
			exit();
		}
	}
}
