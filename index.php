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
    $review_form = '
    <form id="login-window" action="processa_recensione.php" method="post">
        <h1>Facci sapere la tua esperienza</h1>
        <label for="voto">Voto:</label>
        <select class="login-button" name="voto" required>
            <option value=""> Seleziona </option>
            <option value="1">1 - Pessimo</option>
            <option value="2">2 - Scarso</option>
            <option value="3">3 - Sufficiente</option>
            <option value="4">4 - Buono</option>
            <option value="5">5 - Eccellente</option>
        </select>

        <label for="recensione">Recensione:</label><br>
        <textarea id="recensione" name="recensione" placeholder="Scrivi qui la tua recensione..." required></textarea>

        <button class="login-button" type="submit" onkeydown="enter">Invia</button>
    </form>';

    $DOM = str_replace('<div id="ins_recensioni"></div>', $review_form, $DOM);
}

echo($DOM);
?>