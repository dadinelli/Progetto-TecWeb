<?php
session_start();
if ($_SESSION['is_logged_in'] === true) {
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
            echo "
                <ul id='personal-data'>
                    <li>Username: $username</li>
                    <li>Nome: $nome</li>
                    <li>Cognome: $cognome</li>
                    <li>Mail: $email</li>
                    <li>Tel: $telefono</li>
                </ul>
            ";
        } else {
            echo "Cliente non trovato. $idCliente";
        }
    } catch (PDOException $e) {
        echo "Errore di connessione: " . $e->getMessage();
    }
} else {
    echo "Utente non loggato o ID Cliente non trovato nella sessione.";
}

$DOM = file_get_contents("html/private.html");
echo $DOM;

?>

