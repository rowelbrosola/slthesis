<?php
require_once 'app/init.php';

$redirect = new Redirect;
$redirect->name = 'Rowl';
var_dump($redirect->name);exit;
Redirect::to('app/login.php');