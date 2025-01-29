<?php
if (isset($_SESSION['is_logged_in']) || $_SESSION['is_logged_in'] !== true) {
    header("Location: index.php");
    exit();
}else{
    header("Location: login.php");
}
?>