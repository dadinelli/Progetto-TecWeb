<?php
session_start();
header('Content-Type: application/json');

// Connessione al DB
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
    echo json_encode(["success" => false, "message" => "Connessione fallita: " . $e->getMessage()]);
    exit();
}

// Verifica che l'utente sia loggato
if (!isset($_SESSION['ID_Cliente'])) {
    echo json_encode(["success" => false, "message" => "Utente non loggato."]);
    exit();
}

$userID = $_SESSION['ID_Cliente'];

if (!isset($_GET['id'])) {
    echo json_encode(["success" => false, "message" => "ID recensione mancante."]);
    exit();
}

$reviewID = $_GET['id'];

try {
    $deleteQuery = "DELETE FROM Recensione
                    WHERE ID_Recensione = :rid
                      AND ID_Cliente = :uid";
    $stmt = $pdo->prepare($deleteQuery);
    $stmt->bindParam(':rid', $reviewID, PDO::PARAM_INT);
    $stmt->bindParam(':uid', $userID, PDO::PARAM_INT);
    $stmt->execute();

    if ($stmt->rowCount() > 0) {
        echo json_encode(["success" => true, "message" => "Recensione eliminata con successo!"]);
    } else {
        echo json_encode(["success" => false, "message" => "Nessuna recensione eliminata (non era la tua?)."]);
    }
} catch (PDOException $e) {
    echo json_encode(["success" => false, "message" => "Errore nell'eliminazione: " . $e->getMessage()]);
}
?>
