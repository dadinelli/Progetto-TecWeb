<?php 
session_start();
header("Content-type: application/json");

$risultato = false;

if(isset($_POST['checkEmail'])){
    
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
    if(isset($_SESSION['is_logged_in']) && $_SESSION['is_logged_in'] === true){
        $idCliente = $_SESSION['ID_Cliente'];
        $sqlCheckEmail = "SELECT * FROM Cliente WHERE Email = :email AND ID_Cliente != :id_cliente";
        $stmt = $pdo->prepare($sqlCheckEmail);
        $stmt->bindParam(':email', $EmailRicevuta, PDO::PARAM_STR);
        $stmt->bindParam(':id_cliente', $idCliente, PDO::PARAM_STR);
    }
    else {
        $sqlCheckEmail = "SELECT * FROM Cliente WHERE Email = :email";
        $stmt = $pdo->prepare($sqlCheckEmail);
        $stmt->bindParam(':email', $EmailRicevuta, PDO::PARAM_STR);
    }
    
    $stmt->execute();
    if($stmt->rowCount() > 0){ //email già registrata
        $risultato = true;
    }
    echo json_encode(["result" => $risultato]);
}

?>


