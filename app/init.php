<?php
use Illuminate\Database\Capsule\Manager as Capsule;

// To display php erros
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// start session
session_start();

require_once 'app/vendor/autoload.php';

$capsule = new Capsule;


// dev
// $capsule->addConnection([
//     'driver'    => 'mysql',
//     'host'      => 'localhost',
//     'database'  => 'ppcm',
//     'username'  => 'root',
//     'password'  => 'root',
//     'charset'   => 'utf8',
//     'collation' => 'utf8_unicode_ci',
//     'prefix'    => '',
// ]);

// production
$capsule->addConnection([
    'driver'    => 'mysql',
    'host'      => 'us-cdbr-iron-east-04.cleardb.net',
    'database'  => 'heroku_02dfbe5bfd390b9',
    'username'  => 'b19dc4f4a9c5a7',
    'password'  => '0bd4cf8c',
    'charset'   => 'utf8',
    'collation' => 'utf8_unicode_ci',
    'prefix'    => '',
]);

// Make this Capsule instance available globally via static methods... (optional)
$capsule->setAsGlobal();

// Setup the Eloquent ORM... (optional; unless you've used setEventDispatcher())
$capsule->bootEloquent();

$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->load();