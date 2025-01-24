<?php
session_start();

$DOM = file_get_contents("html/index.html");

if(!isset($_SESSION["is_logged_in"])|| $_SESSION["is_logged_in"] == false){
    $login_redirecting = '
    <div id="login-window">
        <h2>Se vuoi farci sapere la tua esperienza <a href="area-riservata.php">accedi</a>!</h2>
    </div>';

    $DOM = str_replace('<section id="ins_recensioni"></section>', $login_redirecting, $DOM);
}
else{
    $review_form = '
    <form id="login-window" action="processa_recensione.php" method="post">
        <h1>Facci sapere la tua esperienza</h1>
        <label for="voto">Voto:</label>
        <select id="voto" name="voto" required>
            <option value="1">1 - Pessimo</option>
            <option value="2">2 - Scarso</option>
            <option value="3">3 - Sufficiente</option>
            <option value="4">4 - Buono</option>
            <option value="5">5 - Eccellente</option>
        </select>

        <label for="recensione">Recensione:</label><br>
        <textarea id="recensione" name="recensione" rows="5" cols="40" placeholder="Scrivi qui la tua recensione..." required></textarea>

        <button class="login-button" type="submit">Invia</button>
        <h2>Non hai un account? <a href="registrazione.php">Registrati</a></h2>
    </form>';

    $DOM = str_replace('<section id="ins_recensioni"></section>', $review_form, $DOM);
}

echo($DOM);
?>