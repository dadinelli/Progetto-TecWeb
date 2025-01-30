<?php

$DOM = file_get_contents("html/admin.html");

//if(isset($_SESSION)){
    
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

    $connection = new mysqli($host, $userdbname, $passwordDB, $dbname);

    $all_reservations = "SELECT * 
                                from Prenotazione JOIN Cliente on Prenotazione.ID_Cliente = Cliente.ID_Cliente
                                WHERE Data >= CurDate()
                                ORDER BY Data";
    $stmt = $pdo->prepare($all_reservations);
    $stmt->execute();
    
    $reservationList = $stmt->fetchAll(PDO::FETCH_ASSOC);

    $result = "";

    if($stmt->rowCount() > 0){
        for($i = 0; $i < $stmt->rowCount(); $i++){
            $res = $reservationList[$i];
            $id = $res['ID_Prenotazione'];
            $nome = $res['Nome'];
            $cognome = $res['Cognome'];
            $telefono = $res['Telefono'];
            $data = $res['Data'];
            $ora = $res['Ora'];
            $numPersone = $res['Numero_Persone'];
            
            $reservation = "
            <div id='login-window'>
            <h2>$nome $cognome - $id</h2>
            <ul>
                <li>Telefono: $telefono</li>
                <li>Data: $data</li>
                <li>Ora: $ora</li>
                <li>N. persone: $numPersone</li>
            </ul>
            </div>
            ";

            $result = $result.$reservation;
        }

        $DOM = str_replace("<div id='show-reservation'></div>", $result, $DOM);
    }
    else{
        $DOM = str_replace("<div id='show-reservation'></div>", "<div id='login-window' class='with-margin'><h2>Nessuna prenotazione attiva</h2></div>", $DOM);
    }
//}

echo($DOM);
?>
