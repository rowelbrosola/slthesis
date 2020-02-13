<?php
require_once('myapp/init.php');
use App\Redirect;

$redirect = new Redirect;
$redirect->name = 'Rowl';
var_dump($redirect->name);exit;
Redirect::to('myapp/login.php');