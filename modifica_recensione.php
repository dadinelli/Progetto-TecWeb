<?php
session_start();
header('Content-Type: application/json');

// Connessione al DB
$host = 'localhost';
$dbname = 'progettotecweb';
$userdbname = 'root';
$passwordDB = '';

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

// Verifica parametri POST
if (!isset($_POST['id'], $_POST['voto'], $_POST['testo'])) {
    echo json_encode(["success" => false, "message" => "Parametri mancanti."]);
    exit();
}

$reviewID = $_POST['id'];
$voto = $_POST['voto'];
$testo = $_POST['testo'];

try {
    $updateQuery = "UPDATE Recensione
                    SET Valutazione = :v, Testo = :t
                    WHERE ID_Recensione = :rid
                      AND ID_Cliente = :uid";
    $stmt = $pdo->prepare($updateQuery);
    $stmt->bindParam(':v', $voto, PDO::PARAM_INT);
    $stmt->bindParam(':t', $testo, PDO::PARAM_STR);
    $stmt->bindParam(':rid', $reviewID, PDO::PARAM_INT);
    $stmt->bindParam(':uid', $userID, PDO::PARAM_INT);
    $stmt->execute();

    // Controllo righe modificate (opzionale)
    if ($stmt->rowCount() > 0) {
        echo json_encode(["success" => true, "message" => "Recensione aggiornata con successo!"]);
    } else {
        echo json_encode(["success" => false, "message" => "Nessuna recensione modificata (forse non appartiene a te)."]);
    }
} catch (PDOException $e) {
    echo json_encode(["success" => false, "message" => "Errore durante l'aggiornamento: " . $e->getMessage()]);
}
?>
