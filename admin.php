<?php

$DOM = file_get_contents("html/admin.html");

if(isset($_SESSION)){
    
    //connessione al db
    $host = 'localhost';                         
    $dbname = 'progettotecweb';          
    $userdbname = 'root';          
    $passwordDB = '';
    try {
        $dsn = "mysql:host=$host;dbname=$dbname;charset=utf8mb4";
        $pdo = new PDO($dsn, $userdbname, $passwordDB);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    } catch (PDOException $e) {
        echo "Connessione fallita: " . $e->getMessage();
        exit(); //se connessione al database è fallita esce dal flusso 
    }

    $all_reservations = "SELECT * from Prenotazione";
    $stmt = $pdo->prepare($sqlCheckEmail);
    $stmt->execute();
    
    $reservationList;

    if($stmt->rowCount()>0){
        while($row = mysqli_fetch_assoc($stmt)){
            $reservation = "
            <div id='login-window'>
            <h2>ID Prenotazione: $id</h2>
            <ul>
                <li>Nome: $name</li>
                <li>Cognome: $cognome</li>
                <li>Telefono: $telefono</li>
                <li>Data: $date</li>
                <li>Ora: $ora</li>
                <li>N. persone: $numPersone</li>
            </ul>
            </div>
            ";
    
            $reservationList.$reservation;

            $DOM = str_replace("<div id='show-reservation'></div>", $reservationList, $DOM);
        }
    }
    else{
        $DOM = str_replace("<div id='show-reservation'></div>", "<h2>Nessuna prenotazione attiva</h2>", $DOM);
    }

    
}

echo($DOM);
?>