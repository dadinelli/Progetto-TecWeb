<?php

session_start();

include 'session_timeout.php';

$DOM = file_get_contents("html/menu.html");
echo($DOM);
?>