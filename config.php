<?php
require_once 'vendor/autoload.php';

session_start();

$cfg = new \Spot\Config();
// MySQL
$adapter = $cfg->addConnection('test_mysql', 'mysql://root:@127.0.0.1/ppcm');