<?php 
// required here to use functions throughout
require ('functions.php'); 

// needed for .env file
require_once __DIR__ . '/vendor/autoload.php';
$dotenv = new Dotenv\Dotenv(__DIR__);
$dotenv->load();

$array = explode("/", $_SERVER['REQUEST_URI']);
$hidden = urldecode(end($array));
// go to view page
require ('view.php');
?>
