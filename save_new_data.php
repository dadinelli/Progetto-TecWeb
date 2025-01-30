<?php
session_start();
$DOM = file_get_contents("html/private.html");

function pulisciInput($value){
    $value = trim($value); //toglie spazi
    $value = strip_tags($value); //rimossi tag html e php
    $value = htmlentities($value); //converte caratteri speciali in entità html
    return $value;
}

if ($_SESSION['is_logged_in'] === true) {
    $idCliente = $_SESSION['ID_Cliente'];

    $formValido = true;
    //inizializzazione variabili form
    $username = '';
    $email = '';
    $telefono = '';
    $password_attuale = '';
    $password_nuova = '';
    $conferma_password_nuova = '';

    if($_SERVER['REQUEST_METHOD']=="POST"){ //bottone submit premuto
        //controllo input 

        $username = pulisciInput($_POST['username']);
        if(strlen($username) == 0){
            $formValido = false;
        }

        $email = pulisciInput($_POST['email']);
        $email_filtred = filter_input(INPUT_POST, 'email', FILTER_VALIDATE_EMAIL);
        if(strlen($email) == 0){
            $formValido = false;
        }
        else if(!$email_filtred){ //controlla che l'email sia scritta nel formato corretto
            $formValido = false;
        }

        $telefono = pulisciInput($_POST['tel']);
        if(strlen($telefono) == 0){
            $formValido = false;
        }
        else if(!preg_match('/^\+?[0-9]{10,15}$/', $telefono)){
            $formValido = false;
        }

        $password_attuale = trim($_POST['password-attuale']); //non usa pulisciInput perchè potrebbe togliere caratteri importanti
        if(strlen($password_attuale) < 8){
            $formValido = false;
        }

        $password_nuova = trim($_POST['nuova-password']); //non usa pulisciInput perchè potrebbe togliere caratteri importanti
        if(strlen($password_nuova) < 8){
            $formValido = false;
        }

        $conferma_password_nuova = trim($_POST['confirm-new-password']);
        if(strlen($conferma_password_nuova) == 0){
            $formValido = false;
        }

        if($password_nuova !== $conferma_password_nuova){
            $formValido = false;
        }
        $password_nuova = password_hash($password_nuova, PASSWORD_DEFAULT); // Hash della nuova password

        if($formValido){ //se il form è valido provo a connettermi al database
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
            //connessione al db riuscita
            //controllo che l'email inserita non sia già stata utilizzata da un utente con id diverso
            $sqlCheckEmail = "SELECT * FROM Cliente WHERE Email = :email AND ID_Cliente != :id_cliente";
            $stmt = $pdo->prepare($sqlCheckEmail);
            $stmt->bindParam(':email', $email, PDO::PARAM_STR);
            $stmt->bindParam(':id_cliente', $idCliente, PDO::PARAM_STR);
            $stmt->execute();
            //controllo che l'username inserito non sia già stato utilizzato da un utente con id diverso
            $sqlCheckUsername = "SELECT * FROM Cliente WHERE Username = :username AND ID_Cliente != :id_cliente";
            $stmt2 = $pdo->prepare($sqlCheckUsername);
            $stmt2->bindParam(':username', $username, PDO::PARAM_STR);
            $stmt2->bindParam(':id_cliente', $idCliente, PDO::PARAM_STR);
            $stmt2->execute();
            //controllo che il telefono inserito non sia già stato utilizzato da un utente con id diverso
            $sqlCheckTel = "SELECT * FROM Cliente WHERE Telefono = :telefono AND ID_Cliente != :id_cliente";
            $stmt3 = $pdo->prepare($sqlCheckTel);
            $stmt3->bindParam(':telefono', $telefono, PDO::PARAM_STR);
            $stmt3->bindParam(':id_cliente', $idCliente, PDO::PARAM_STR);
            $stmt3->execute();
            if ($stmt->rowCount() > 0) { //email già stata utilizzata
                echo "email già utilizzata da un altro utente";
                header("Location: private.php");
                exit();    
            }
            if ($stmt2->rowCount() > 0) { //email già stata utilizzata
                echo "username già utilizzato da un altro utente";
                header("Location: private.php");
                exit();
            }
            if ($stmt3->rowCount() > 0) { //email già stata utilizzata
                echo "telefono già utilizzato da un altro utente";
                header("Location: private.php");
                exit();
            }
            //email, username e telefono non ancora registrati da un altro utente
            //controllo che la password attuale inserita dall'utente corrisponda alla password salvata su db
            $sqlGetClient = "SELECT * FROM Cliente WHERE ID_Cliente = :idCliente";
            $stmt = $pdo->prepare($sqlGetClient);
            $stmt->bindParam(':idCliente', $idCliente, PDO::PARAM_STR);
            $stmt->execute();
            if ($stmt->rowCount() > 0){ //ho ottenuto il cliente che sta cambiando i dati
                $cliente = $stmt->fetch(PDO::FETCH_ASSOC);
                $hash_password_cliente = $cliente['Pass'];
                if(!password_verify($password_attuale, $hash_password_cliente)){
                    echo "Password attuale inserita errata";
                }
                else { //passati tutti i controlli, email non utilizzata da nessun altro e password attuale inserita corretta
                    //posso fare update dei dati del cliente su db
                    $sqlUpdate = "UPDATE Cliente SET Email = :email, Telefono = :telefono, Username = :username, Pass = :password  WHERE ID_Cliente = :idCliente";          
                    $stmt = $pdo->prepare($sqlUpdate);
                    $stmt->bindParam(':email', $email, PDO::PARAM_STR);
                    $stmt->bindParam(':telefono', $telefono, PDO::PARAM_STR);
                    $stmt->bindParam(':username', $username, PDO::PARAM_STR);
                    $stmt->bindParam(':password', $password_nuova, PDO::PARAM_STR);
                    $stmt->bindParam(':idCliente', $idCliente, PDO::PARAM_STR);
                    if($stmt->execute()){
                        $_SESSION['success_update'] = "Modifica credenziali avvenuta con successo!";
                        header("Location: private.php");
                        exit();
                    }
                    else { 
                        $_SESSION['error_update'] = "Abbiamo avuto un problema con la modifica delle credenziali";
                        header("Location: private.php");
                        exit();
                    }
                }
            }
        } 
        else{
            //faccio visualizzare i messaggi di errore del form
            echo "form non valido";
        }
        header("Location: private.php");
    }
}
?>