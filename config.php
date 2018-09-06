<?php 
    require_once __DIR__ . '/vendor/autoload.php';
    // require config for database
    require_once ('config.php');
    $dotenv = new Dotenv\Dotenv(__DIR__);
    $dotenv->load();

    define('DB_HOST', 'localhost');
    define('DB_USER', getenv('USER_HIDDEN'));
    define('DB_PASS', getenv('PASSWORD'));
    define('DB_DATABASE', 'shows');

    //connect to database host
    $connection = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_DATABASE); 

?>