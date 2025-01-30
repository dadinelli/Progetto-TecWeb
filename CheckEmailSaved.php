<?php 
header("Content-type: application/json");

$risultato = false;

if(isset($_POST['checkEmail'])){
    
    $emailRicevuta = $_POST['checkEmail'];
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

    $sqlCheckEmail = "SELECT * FROM Cliente WHERE Email = :email";
    $stmt = $pdo->prepare($sqlCheckEmail);
    $stmt->bindParam(':email', $emailRicevuta, PDO::PARAM_STR);
    $stmt->execute();
    if($stmt->rowCount() > 0){ //email già registrata
        $risultato = true;
    }
    echo json_encode(["result" => $risultato]);
}

?>


