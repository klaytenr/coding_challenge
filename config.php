<?php 

    define('DB_HOST', 'localhost');
    define('DB_USER', 'root');
    define('DB_PASS', 'root');
    define('DB_DATABASE', 'shows');

    //connect to database host
    $connection = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_DATABASE); 

?>