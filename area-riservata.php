<?php
session_start();

if(!isset($_SESSION["is_logged_in"]) || $_SESSION["is_logged_in"] == false){
    $DOM = file_get_contents("html/area-riservata.html");
    echo($DOM);
}
else{
    header("Location: private.php");
}
?>