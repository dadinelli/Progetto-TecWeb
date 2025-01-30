<?php
session_start();

include 'session_timeout.php';

$DOM = file_get_contents("html/private.html");

//Load user data
if ($_SESSION['is_logged_in'] === true) {
    if(!isset($_SESSION['data_update'])){
        $_SESSION['data_update'] = '';
    }

    if($_SESSION['ruolo'] != 'Cliente') header("Location: admin.php");

    $idCliente = $_SESSION['ID_Cliente'];

    //$host = 'localhost';
    $host = 'localhost';                          
    //$dbname = 'progettotecweb';
    $dbname = 'damartin';            
    //$userdbname = 'root';  
    $userdbname = 'damartin';        
    $passwordDB = 'Doo3ieD4yoS7ienu';

    try {
        $dsn = "mysql:host=$host;dbname=$dbname;charset=utf8mb4";
        $pdo = new PDO($dsn, $userdbname, $passwordDB);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        
        $stmt = $pdo->prepare("SELECT * FROM Cliente WHERE ID_Cliente = :idCliente");
        $stmt->bindParam(':idCliente', $idCliente, PDO::PARAM_INT);
        $stmt->execute();

        if ($stmt->rowCount() > 0) {
            $cliente = $stmt->fetch(PDO::FETCH_ASSOC);

            $nome = $cliente['Nome'];
            $cognome = $cliente['Cognome'];
            $email = $cliente['Email'];
            $telefono = $cliente['Telefono'];
            $username = $cliente['Username'];
            $_SESSION['nome'] = $nome;
            $_SESSION['email'] = $email;
            $_SESSION['telefono'] = $telefono;
            $_SESSION['Username'] = $username;
            $_SESSION['Cognome'] = $cognome;

            $show_data = "
                <ul id='personal-data'>
                    <li>Username: $username</li>
                    <li>Nome: $nome</li>
                    <li>Cognome: $cognome</li>
                    <li>Mail: $email</li>
                    <li>Tel: $telefono</li>
                </ul>
            ";

            $DOM = str_replace('<div id="content"></div>', $show_data, $DOM);

            $data_update = $_SESSION['data_update'];
            $DOM = str_replace('<div id="dataUpdate"></div>', $data_update, $DOM);

            $DOM = str_replace("value='email'", "value='$email'", $DOM);
            $DOM = str_replace("value='username'", "value='$username'", $DOM);
            $DOM = str_replace("value='telefono'", "value='$telefono'", $DOM);
        } else {
            echo "Cliente non trovato. $idCliente";
        }
    } catch (PDOException $e) {
        echo "Errore di connessione: " . $e->getMessage();
    }
} else {
    echo "Utente non loggato o ID Cliente non trovato nella sessione.";
    header("Location: area-riservata.php");
}

/*include "prenota.php";
if(isset($_POST['submit'])){
    reservation($DOM);
}*/

echo($DOM);

$_SESSION['data_update'] = '';
?>

