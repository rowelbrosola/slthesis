<?php
require_once __DIR__.'/../vendor/autoload.php';
use Illuminate\Database\Capsule\Manager as Capsule;

// To display php erros
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// start session
session_start();

$capsule = new Capsule;

if ($_SERVER['HTTP_HOST'] === 'localhost') {
    // //dev
    $capsule->addConnection([
        'driver'    => 'mysql',
        'host'      => 'localhost',
        'database'  => 'ppcm',
        'username'  => 'root',
        'password'  => 'root',
        'charset'   => 'utf8',
        'collation' => 'utf8_unicode_ci',
        'prefix'    => '',
    ]);
} else {
    //production
    $capsule->addConnection([
        'driver'    => getenv('DB_DRIVER'),
        'host'      => getenv('DB_HOST'),
        'database'  => getenv('DB_DATABASE'),
        'username'  => getenv('DB_USERNAME'),
        'password'  => getenv('DB_PASSWORD'),
        'charset'   => 'utf8',
        'collation' => 'utf8_unicode_ci',
        'prefix'    => '',
    ]);
}

// Make this Capsule instance available globally via static methods... (optional)
$capsule->setAsGlobal();

// Setup the Eloquent ORM... (optional; unless you've used setEventDispatcher())
$capsule->bootEloquent();
if(file_exists(__DIR__ . '/.env')) {
    $dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
    $dotenv->load();
}