<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);


header('Access-Control-Allow-Origin: http://spotware-client.local');  
header('Access-Control-Allow-Methods: POST, GET, OPTIONS, PUT, DELETE');
header('Access-Control-Allow-Headers: Content-Type');
header('Access-Control-Allow-Credentials: true');
 
//error_reporting(0);

session_start();
include_once './config/config.php';
include_once './lib/functions.php';

$router = new RestRouter();
$router->route();