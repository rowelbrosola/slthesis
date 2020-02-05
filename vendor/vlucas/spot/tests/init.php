<?php
/**
* @package Spot
*/

error_reporting(-1);
ini_set('display_errors', 1);

require dirname(dirname(__FILE__)) . '/vendor/autoload.php';

// Date setup
date_default_timezone_set('America/Chicago');

// Setup available adapters for testing
$cfg = new \Spot\Config();
$db_type = getenv('SPOT_DB_TYPE');
$db_dsn  = getenv('SPOT_DB_DSN');

if (!empty($db_type)) {
    $cfg->addConnection('test', $db_dsn);
} else {
    die('DSN not configured');
}

/**
* Return Spot mapper for use
*/
$mapper = new \Spot\Mapper($cfg);
function test_spot_mapper() {
    global $mapper;
    return $mapper;
}

/**
* Autoload test fixtures
*/
function test_spot_autoloader($className) {
    // Only autoload classes that start with "Test_" and "Entity_"
    if(false === strpos($className, 'Test_') && false === strpos($className, 'Entity_')) {
        return false;
    }
    $classFile = str_replace('_', '/', $className) . '.php';
    require __DIR__ . '/' . $classFile;
}
spl_autoload_register('test_spot_autoloader');
