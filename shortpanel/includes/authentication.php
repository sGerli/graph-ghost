<?php
// Import the necessary classes
use Cartalyst\Sentinel\Native\Facades\Sentinel;
use Illuminate\Database\Capsule\Manager as Capsule;
require_once($_SERVER["DOCUMENT_ROOT"] . "/secret.php");
require_once($_SERVER["DOCUMENT_ROOT"] . "/vendor/autoload.php");

// Setup a new Eloquent Capsule instance
$capsule = new Capsule;

$capsule->addConnection([
    'driver'    => 'mysql',
    'host'      => SERVERNAME,
    'database'  => DATABASE,
    'username'  => USERNAME,
    'password'  => PASSWORD,
    'charset'   => 'utf8',
    'collation' => 'utf8_unicode_ci',
]);

$capsule->bootEloquent();

Sentinel::removeCheckpoint('activation');

/*Sentinel::register([
    'username'    => 'sgerli',
    'password' => 'test',
]);*/
?>