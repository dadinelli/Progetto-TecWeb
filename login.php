<?php
$durata_sessione = 5;
session_set_cookie_params($durata_sessione * 60);
session_start();
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Continua con la logic
    $username = $_POST['username'];
    $password = $_POST['password'];
    if(strlen($password) < 8){
        //Aggiornate con avviso nel html che devono avere minimo 8 caratteri la password
        header("Location: area-riservata.html");
    }
    /*if(!preg_match('/[\W_]/', $password)){  //dovrebbe controllare se contiene almeno un carattere speciale
        //Aggiornate con avviso nel html che devono avere almeno un carattere speciale
        header("Location: area-riservata.html");
    }
    if(!preg_match('/\d/', $password)){  // Controlla se contiene almeno un numero
        // Aggiorna con avviso nel html che deve avere almeno un numero
        header("Location: area-riservata.html");
        exit();
    }*/
    $host = 'localhost';
    $port = '5432';
    $dbname = 'progettotecweb';
    $userdbname = 'root';
    $passwordDB = '';
    try {
        /*$dsn = "pgsql:host=$host;port=$port;dbname=$dbname";
        $pdo = new PDO($dsn, $userdbname, $passwordDB);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $con = true;
        */
        $dsn = "mysql:host=$host;dbname=$dbname;charset=utf8mb4";
        $pdo = new PDO($dsn, $userdbname, $passwordDB);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $con = true;
    } catch (PDOException $e) {
        echo "Errore di connessione: " . $e->getMessage();
        $con = false;
    }
    // Controlla se il modulo è stato inviato
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && $con == true) {
        $username = $_POST['username'] ?? '';
        $password = $_POST['password'] ?? '';
        $stmt = $pdo->prepare("SELECT * FROM cliente WHERE username = :username");
        $stmt->bindParam(':username', $username, PDO::PARAM_STR);
        $stmt->execute();
        if ($stmt->rowCount() > 0) {
            $user = $stmt->fetch(PDO::FETCH_ASSOC);
            if ($password == $user['Pass'] ||password_verify($password, $user['Pass'])){
                $_SESSION['username'] = $username;
                $_SESSION['ID_Cliente'] = $user['ID_Cliente'];
                $_SESSION['is_logged_in'] = true; //per capire se è loggato o no
                header("Location: private.html");
                exit();
            } else {
                echo "Password errata.            ";
                echo "La tua password : $password";
                echo "password effettiva : ".$user['Pass'];
            }
        } else {
            echo "Utente non trovato.";
        }
    } 
}
?>
























