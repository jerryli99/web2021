<?php
//connect to database and submit data from register.php
require_once 'vendor/autoload.php';
$dotenv= Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->safeLoad();

// the dotenv worked!!!
$connect = mysqli_connect('localhost', '', '', '');

//check connection
if($connect -> connect_error){
//    die("Connection failed: " . $connect -> connect_error . "<br>");
}


