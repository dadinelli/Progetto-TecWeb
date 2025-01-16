<?php
if (isset($_SESSION['is_logged_in']) || $_SESSION['is_logged_in'] !== true) {
    header("Location: index.html");
    exit();
}else{
    header("Location: login.php");
}
?>