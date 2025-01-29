<?php
session_start();

$DOM = file_get_contents("html/index.html");

if(!isset($_SESSION["is_logged_in"])|| $_SESSION["is_logged_in"] == false){
    $login_redirecting = '
    <div id="login-window">
        <h2>Se vuoi farci sapere la tua esperienza <a href="area-riservata.php">accedi</a>!</h2>
    </div>';

    $DOM = str_replace('<div id="ins_recensioni"></div>', $login_redirecting, $DOM);
}
else{
    $review_form = file_get_contents("html/reservation_form.html");

    $DOM = str_replace('<div id="ins_recensioni"></div>', $review_form, $DOM);
}

echo($DOM);
?>