<?php
session_start();

include 'session_timeout.php';

if(!isset($_SESSION["is_logged_in"]) || $_SESSION["is_logged_in"] == false){
    $DOM = file_get_contents("html/area-riservata.html");
    echo($DOM);
}
else{
    if($_SESSION['ruolo'] == 'Cliente'){
        header("Location: private.php");
    }else{
        header("Location: admin.php");
    }
}
?>
