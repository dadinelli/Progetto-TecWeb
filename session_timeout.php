<?php

if(isset($_SESSION['is_logged_in']) && $_SESSION['is_logged_in'] == true){ //controllo se l'utente è loggato 
    if (isset($_SESSION['LAST_ACTIVITY']) && (time() - $_SESSION['LAST_ACTIVITY'] > 1800)) {
        // ultima richeista 30 minuti fa
        session_unset();     // array di sessione svuotato
        session_destroy();  // distruzione dati di sessione
        //$DOM = file_get_contents("html/area-riservata.html");
        //echo($DOM);   //se un utente era loggato ma è passato troppo tempo, lo rimando alla pagina di login
    }
    $_SESSION['LAST_ACTIVITY'] = time();  //aggiorno il dato temporale dell'ultima attività
}
?>