<?php
require "vendor/autoload.php";
include_once ("settings.php");
use Illuminate\Database\Capsule\Manager as Capsule;
$capsule = new Capsule;
$capsule->addConnection([
    "driver" => "mysql",
    "host" => "$DB_HOST",
    "database" => "$DB_DATABASE",
    "username" => "$DB_USERNAME",
    "password" => "$DB_PASSWORD"
]);
$capsule->setAsGlobal();
$capsule->bootEloquent();
