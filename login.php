<?php
session_set_cookie_params(3600);
session_start();
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Continua con la logic
    $username = $_POST['username'];
    $password = $_POST['password'];

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
        $con = true;
    } catch (PDOException $e) {
        echo "Errore di connessione: " . $e->getMessage();
        $con = false;
    }
    // Controlla se il modulo è stato inviato
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && $con == true) {
        $username = $_POST['username'] ?? '';
        $password = $_POST['password'] ?? '';
        $stmt = $pdo->prepare("SELECT * FROM Cliente WHERE username = :username");
        $stmt->bindParam(':username', $username, PDO::PARAM_STR);
        $stmt->execute();
        if ($stmt->rowCount() > 0) {
            $user = $stmt->fetch(PDO::FETCH_ASSOC);
            if (password_verify($password, $user['Pass'])){

                $_SESSION['username'] = $username;
                $_SESSION['ID_Cliente'] = $user['ID_Cliente'];
                $_SESSION['is_logged_in'] = true; //per capire se è loggato o no
                $_SESSION['ruolo'] = $user['Ruolo'];
                if($user['Ruolo'] == 'Cliente')
                    header("Location: private.php");
                else 
                    header("Location: admin.php");
                exit();
            } 
            else{
                header("Location: permission_denied.php");
            }

        } else {
            header("Location: permission_denied.php");
        }
    } 
}
?>
























