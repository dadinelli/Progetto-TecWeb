<?php
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
    $sql = "SELECT c.Username, r.Valutazione, r.Testo, r.Data FROM recensione r JOIN cliente c ON r.ID_Cliente=c.ID_Cliente 
            ORDER BY r.Valutazione DESC LIMIT 3";
    $stmt = $pdo->prepare($sql);
    $stmt->execute();
    $recensioni = $stmt->fetchAll(PDO::FETCH_ASSOC);
    header('Content-Type: application/json');
    echo json_encode($recensioni);

?>