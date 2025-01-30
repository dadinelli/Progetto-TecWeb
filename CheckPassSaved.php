<?php 
session_start();
header("Content-type: application/json");

$risultato = false;

if(isset($_POST['checkPass'])){
    if(isset($_SESSION['is_logged_in']) && $_SESSION['is_logged_in'] === true){
        $idCliente = $_SESSION['ID_Cliente'];
    }
   
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
    } catch (PDOException $e) {
        echo "Connessione fallita: " . $e->getMessage();
        exit(); //se connessione al database è fallita esce dal flusso 
    }
    
    $sqlGetUser = "SELECT * FROM Cliente WHERE ID_Cliente = :idCliente";
    $stmt = $pdo->prepare($sqlGetUser);
    $stmt->bindParam(':idCliente', $idCliente, PDO::PARAM_STR);
    $stmt->execute();
    
    if($stmt->rowCount() > 0){ //ottengo il cliente e controllo che le password combacino
        $cliente = $stmt->fetch(PDO::FETCH_ASSOC);
        $hash_password_cliente = $cliente['Pass'];
        if(!password_verify($passRicevuta, $hash_password_cliente)){ //la password attuale inserita non è corretta
        $risultato = true;
        }
    }
    echo json_encode(["result" => $risultato]);
}

?>