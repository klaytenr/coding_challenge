<?php 
// required to use functions
require ('functions.php'); 


// autocomplete function
if(isset($_GET['shows'])){
    getAll($_GET['shows']);
}
// regular show request
if(isset($_GET['show'])){
    if(!isset($_SESSION['token'])){
        curlPost('login', PARAMS);
    }
    // get the name of the show and replace spaces with symbol for api to read
    $show = $_GET['show'];
    $shows = str_replace(' ', '%20', $show);
    // call on curl request in functions
    curl('search/series?name=' . $shows);
}
?>