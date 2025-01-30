<?php
session_set_cookie_params(3600);
session_start();
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Continua con la logic
    $username = $_POST['username'];
    $password = $_POST['password'];

    $host = 'localhost';
    $port = '5432';
    $dbname = 'progettotecweb';
    $userdbname = 'root';
    $passwordDB = '';
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
        $stmt = $pdo->prepare("SELECT * FROM cliente WHERE username = :username");
        $stmt->bindParam(':username', $username, PDO::PARAM_STR);
        $stmt->execute();
        if ($stmt->rowCount() > 0) {
            $user = $stmt->fetch(PDO::FETCH_ASSOC);
            // Verifica della password
            /*-------------------------------------------------------------------------------------------------------------------------------
            Nota!!!!!!!!!!!!!!
            password_verify($password, $user['pass'] <--- da sostituire dopo perchè ora non abbiamo ancora fatto insert con password_hash che 
            nasconde la password, dopo nella registrazione dobbiamo modificarlo e che faccia la roba della hash per la sicurazza.
            --------------------------------------------------------------------------------------------------------------------------------*/
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
























