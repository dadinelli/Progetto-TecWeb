<?php

session_start();

include 'session_timeout.php';

$DOM = file_get_contents("html/contatti.html");
echo $DOM;

?>