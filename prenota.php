<?php
//if (isset($_SESSION["is_logged_in"]) == true && isset($_SESSION["numero-persone"]) && isset($_SESSION["date"]) && isset($_SESSION["orario"])){
//$DOM = file_get_contents('html/private.html');

//function reservation($DOM){

session_start();

$DOM = file_get_contents("html/prenotazione.html");

if (isset($_SESSION["is_logged_in"]) == true){    
    $numPersone = $_POST["numero-persone"];
    $data = $_POST["date"];
    $orarioPranzo = isset($_POST["orariopranzo"]) ? $_POST["orariopranzo"] : null;
    $orarioCena = isset($_POST["orariocena"]) ? $_POST["orariocena"] : null;
    $orario;
    if($orarioPranzo != null){
        $orario = $orarioPranzo;
    }
    if($orarioCena != null){
        $orario = $orarioCena;
    }
    $name = $_SESSION['nome'];
    $email = $_SESSION['email'];
    $telefono = $_SESSION['telefono'];
    $username = $_SESSION['Username'];
    $cognome = $_SESSION['Cognome'];
    $idCliente = $_SESSION['ID_Cliente'];
    $host = 'localhost';           
    //$port = '3306';               
    $dbname = 'progettotecweb';   
    $userdbname = 'root';          
    $passwordDB = '';     
    try {
        $dsn = "mysql:host=$host;dbname=$dbname;charset=utf8mb4";
        $pdo = new PDO($dsn, $userdbname, $passwordDB);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    } catch (PDOException $e) {
        echo "Errore di connessione: " . $e->getMessage();
    }
    //controllo cliente se ha già una prenotazione
    $stmtCheck = $pdo->prepare("SELECT ID_Cliente
                                  FROM Prenotazione
                                  WHERE ID_Cliente = :cliente
                                  AND Data = :datas;
                        ");
    $stmtCheck->bindParam(':cliente',$idCliente, PDO::PARAM_INT);
    $stmtCheck->bindParam(':datas', $data, PDO::PARAM_STR);
    $stmtCheck->execute();
    $CheckExist = $stmtCheck->fetch(PDO::FETCH_ASSOC);
    $CheckExist1 = 0;
    if ($CheckExist !== false) {
        $CheckExist1 = $CheckExist['ID_Cliente'];
    } else {
        echo "Nessuna prenotazione trovata per il cliente.";
    }
    if ($CheckExist1 != null) {
        $reject_reservation = "<h1>Spiacienti, la prenotazione non e' andata a buon fine</h1>";

        $DOM = str_replace("<ul id='user-data-list'></ul>", "", $DOM);
        $DOM = str_replace("<h1></h1>", $reject_reservation, $DOM);
        $new_message = "Motivo : Hai già effettuato una prenotazione in questa data, se ha dei cambiamenti la prego di chiamare direttamente alla pizzeria";
        $data_p = "<p>".$new_message."</p>";
        $DOM = str_replace("<p></p>",$data_p, $DOM);

    }else{
        $maxCapacity = 0;
    if ($numPersone == 1) {
        $minCapacity = 2; 
        $maxCapacity = 2;
    } elseif ($numPersone <= 2) {
        $minCapacity = 2; 
        $maxCapacity = 3; 
    } elseif ($numPersone <= 3) {
        $minCapacity = 3; 
        $maxCapacity = 4; 
    } elseif ($numPersone <= 4) {
        $minCapacity = 4; 
        $maxCapacity = 5; 
    } elseif ($numPersone <= 5) {
        $minCapacity = 5;
        $maxCapacity = 6;
    } elseif ($numPersone <= 6) {
        $minCapacity = 6; 
        $maxCapacity = 7;
    } elseif ($numPersone <= 7) {
        $minCapacity = 7; 
        $maxCapacity = 8;
    } elseif ($numPersone <= 8) {
        $minCapacity = 8; 
        $maxCapacity = 9;
    } elseif ($numPersone <= 9) {
        $minCapacity = 10; 
        $maxCapacity = 10;
    } else {
        $minCapacity = 20; 
        $maxCapacity = 20;
    }
    $stmt = $pdo->prepare("SELECT t.ID_Tavolo, t.Numero_Tavolo, t.Capacita
                        FROM Tavoli t
                        WHERE t.Capacita >= :minCapacity AND t.Capacita <= :maxCapacity
                        AND NOT EXISTS (
                            SELECT 1
                            FROM Prenotazione_Tavoli pt
                            JOIN Prenotazione p ON pt.ID_Prenotazione = p.ID_Prenotazione
                            WHERE pt.ID_Tavolo = t.ID_Tavolo
                            AND p.Data = :datas
                            AND (
                                (pt.Ora_Inizio < :orafine AND pt.Ora_Fine > :orainizio)
                            )
                        )
                        ORDER BY t.Capacita ASC
                        LIMIT 1;");
        $orarioDateTime = new DateTime($orario);
        $orarioDateTime->modify('+1 hour'); 
        $orarioFine = $orarioDateTime->format('H:i');
        $stmt->bindParam(':minCapacity', $minCapacity, PDO::PARAM_INT);
        $stmt->bindParam(':maxCapacity', $maxCapacity, PDO::PARAM_INT);
        $stmt->bindParam(':numeropersone', $numPersone, PDO::PARAM_INT);
        $stmt->bindParam(':datas', $data, PDO::PARAM_STR);
        $stmt->bindParam(':orafine',$orarioFine, PDO::PARAM_INT);
        $stmt->bindParam(':orainizio', $orario, PDO::PARAM_INT);
        $stmt->execute();
        $idTavolo = 0;
        if ($stmt->rowCount() > 0) {
            $pdo->beginTransaction();
            $tavolo = $stmt->fetch(PDO::FETCH_ASSOC);
      $idTavolo = $tavolo['ID_Tavolo'];

            //echo "ID Tavolo disponibile: " . $idTavolo . " - Numero Tavolo: " . $tavolo['Numero_Tavolo'];
            
            $stmtPrenotazione = $pdo->prepare("INSERT INTO Prenotazione (data, Ora, Numero_Persone, Stato, ID_Cliente)
                                                    VALUES (:datas, :orario, :numeropersone, 'Confermata', :idcliente)");
            $stmtPrenotazione->bindParam(':datas', $data, PDO::PARAM_STR);
            $stmtPrenotazione->bindParam(':orario', $orario, PDO::PARAM_STR);
            $stmtPrenotazione->bindParam(':numeropersone', $numPersone, PDO::PARAM_INT);
            $stmtPrenotazione->bindParam(':idcliente', $idCliente, PDO::PARAM_INT);
            $stmtPrenotazione->execute();
            $idPrenotazione = $pdo->lastInsertId();
            $stmtPrenotazioneTavoli = $pdo->prepare("
                INSERT INTO Prenotazione_Tavoli (ID_Prenotazione, ID_Tavolo, Ora_Inizio, Ora_Fine)
                VALUES (:idprenotazione, :idtavolo, :orainizio, :orafine)
            ");
            $stmtPrenotazioneTavoli->bindParam(':idprenotazione', $idPrenotazione, PDO::PARAM_INT);
            $stmtPrenotazioneTavoli->bindParam(':idtavolo', $idTavolo, PDO::PARAM_INT);
            $stmtPrenotazioneTavoli->bindParam(':orainizio', $orario, PDO::PARAM_STR);
            $stmtPrenotazioneTavoli->bindParam(':orafine', $orarioFine, PDO::PARAM_STR);
            $stmtPrenotazioneTavoli->execute();
            $pdo->commit();

            $data_list = "<ul id='user-data-list'>
                <li><strong>Username:</strong> $username</li>
                <li><strong>Email:</strong> $email</li>
                <li><strong>Telefono:</strong> $telefono</li>
                <li><strong>Numero di persone:</strong> $numPersone</li>
                <li><strong>Data:</strong> $data</li>
                <li><strong>Orario:</strong> $orario</li>
            </ul>";

            $success_reservation = "<h1>Prenotazione Effettuata!</h1>";
            $data_p = "<p>Grazie, <strong>$name $cognome</strong>, per aver effettuato una prenotazione con noi!</p>";

            $DOM = str_replace("<h1><h1>", $success_reservation, $DOM);
            $DOM = str_replace("<ul id='user-data-list'></ul>", $data_list, $DOM);
            $DOM = str_replace('<p></p>', $data_p, $DOM);
        }else {
            $reject_reservation = "<h1>Spiacienti, la prenotazione non e' andata a buon fine</h1>";

            $DOM = str_replace("<ul id='user-data-list'></ul>", "", $DOM);
            $DOM = str_replace("<h1></h1>", $reject_reservation, $DOM);
            $new_message = "Ci scusi ma al momento i posti sono tutti occupati verso a quest'ora";
            $data_p = "<p>".$new_message."</p>";
            $DOM = str_replace('<p></p>', $data_p, $DOM);
        }
    }
}

//}

echo($DOM);
?>