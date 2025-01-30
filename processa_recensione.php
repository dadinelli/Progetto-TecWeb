<?php 
session_start();
if (isset($_SESSION["is_logged_in"]) == true){
    $valutazione = $_POST['voto'];
    $recensione = '';
    $user_ID = $_SESSION['ID_Cliente'];
    $currentDate = date("Y-m-d");
    $formValido = true;

    function pulisciInput($value){
        $value = trim($value); 
        $value = strip_tags($value); 
        $value = htmlentities($value); 
        return $value;
    }

    if($_SERVER['REQUEST_METHOD'] === "POST"){ 
        $recensione = pulisciInput($_POST['recensione']);
        if (strlen($recensione) == 0) {
            $formValido = false;
        }
        
        if ($formValido) {

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
                exit();
            }

            $sqlCheck = "SELECT ID_Recensione FROM Recensione WHERE ID_Cliente = :id_cliente";
            $stmtCheck = $pdo->prepare($sqlCheck);
            $stmtCheck->bindParam(':id_cliente', $user_ID, PDO::PARAM_INT);
            $stmtCheck->execute();
            $existingReview = $stmtCheck->fetch(PDO::FETCH_ASSOC);

            if ($existingReview) {
                $sqlUpdate = "UPDATE Recensione SET Valutazione = :valutazione, Testo = :testo, Data = :data WHERE ID_Cliente = :id_cliente";
                $stmtUpdate = $pdo->prepare($sqlUpdate);
                $stmtUpdate->bindParam(':valutazione', $valutazione, PDO::PARAM_INT);
                $stmtUpdate->bindParam(':testo', $recensione, PDO::PARAM_STR);
                $stmtUpdate->bindParam(':data', $currentDate, PDO::PARAM_STR);
                $stmtUpdate->bindParam(':id_cliente', $user_ID, PDO::PARAM_INT);
                
                if ($stmtUpdate->execute()) {
                    echo "Recensione aggiornata con successo.";
                    header("Location: index.php");
                    exit();
                } else {
                    echo "Problemi con l'aggiornamento della recensione.";
                    header("Location: index.php");
                    exit();
                }
            } else {
                $sqlInsert = "INSERT INTO Recensione (Valutazione, Testo, Data, ID_Cliente) 
                            VALUES (:valutazione, :testo, :data, :id_cliente)";
                $stmtInsert = $pdo->prepare($sqlInsert);
                $stmtInsert->bindParam(':valutazione', $valutazione, PDO::PARAM_INT);
                $stmtInsert->bindParam(':testo', $recensione, PDO::PARAM_STR);
                $stmtInsert->bindParam(':data', $currentDate, PDO::PARAM_STR);
                $stmtInsert->bindParam(':id_cliente', $user_ID, PDO::PARAM_INT);
                
                if ($stmtInsert->execute()) {
                    echo "Recensione aggiunta con successo.";
                    header("Location: index.php");
                    exit();
                } else {
                    echo "Problemi con l'inserimento della recensione.";
                    header("Location: index.php");
                    exit();
                }
            }
        } else {
            echo "Form non valido";
            header("Location: index.php");
            exit();
        }
    }
} else {
    echo "Effettuare il login";
    header("Location: index.php");
    exit();
}
?>
