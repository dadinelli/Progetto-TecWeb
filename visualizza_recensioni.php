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
    echo json_encode(["error" => "Connessione fallita: " . $e->getMessage()]);
    exit();
}

$response = [];
$response['isLoggedIn'] = isset($_SESSION['ID_Cliente']); // true se l'utente è loggato

$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$limit = 3;
$offset = ($page - 1) * $limit;

// Se utente loggato, recupera la sua ultima recensione
if ($response['isLoggedIn']) {
    $userID = $_SESSION['ID_Cliente'];
    try {
        $sqlUserReview = "SELECT r.ID_Recensione, r.Valutazione, r.Testo, r.Data
                          FROM Recensione r
                          WHERE r.ID_Cliente = :userID
                          ORDER BY r.Data DESC
                          LIMIT 1";
        $stmtUserReview = $pdo->prepare($sqlUserReview);
        $stmtUserReview->bindParam(':userID', $userID, PDO::PARAM_INT);
        $stmtUserReview->execute();
        $userReview = $stmtUserReview->fetch(PDO::FETCH_ASSOC);

        if ($userReview) {
            $response['userReview'] = $userReview;
        } else {
            $response['userReview'] = null;
        }
    } catch (PDOException $e) {
        $response['error'] = "Errore userReview: " . $e->getMessage();
    }
}

// Preleva le recensioni recenti (JOIN con Cliente per recuperare nome, cognome)
try {
    $sqlRecentReviews = "SELECT r.ID_Recensione, r.Valutazione, r.Testo, r.Data,
                                c.ID_Cliente, c.Nome, c.Cognome
                         FROM Recensione r
                         JOIN Cliente c ON r.ID_Cliente = c.ID_Cliente
                         ORDER BY r.Data DESC
                         LIMIT :limit OFFSET :offset";
    $stmtRecentReviews = $pdo->prepare($sqlRecentReviews);
    $stmtRecentReviews->bindParam(':limit', $limit, PDO::PARAM_INT);
    $stmtRecentReviews->bindParam(':offset', $offset, PDO::PARAM_INT);
    $stmtRecentReviews->execute();
    $recentReviews = $stmtRecentReviews->fetchAll(PDO::FETCH_ASSOC);

    $response['recentReviews'] = $recentReviews ? $recentReviews : [];
} catch (PDOException $e) {
    $response['error'] = "Errore recentReviews: " . $e->getMessage();
}

// Conta totale recensioni per paginazione
try {
    $sqlCountReviews = "SELECT COUNT(*) FROM Recensione";
    $stmtCount = $pdo->prepare($sqlCountReviews);
    $stmtCount->execute();
    $totalReviews = $stmtCount->fetchColumn();

    $response['totalReviews'] = $totalReviews;
    $response['totalPages'] = ceil($totalReviews / $limit);
} catch (PDOException $e) {
    $response['error'] = "Errore countReviews: " . $e->getMessage();
}

echo json_encode($response);
