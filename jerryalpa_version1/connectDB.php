<?php
//connect to database and submit data from register.php
require_once 'vendor/autoload.php';
$dotenv= Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->safeLoad();

// the dotenv worked!!!
$connect = mysqli_connect('localhost', 'jerry1', '33678Jerry@ubuntu', 'demo2');
//$connect = new mysqli('127.0.0.1:3307', 'root', '', 'exchangedemo');
//check connection
if($connect -> connect_error){
//    die("Connection failed: " . $connect -> connect_error . "<br>");
}


