<?php
session_start();

$paginaHTML = file_get_contents("html/registrazione.html");
$messaggiForm = ''; //messaggi di errore
$formValido = true;
//inizializzazione variabili form
$nome = '';
$cognome = '';
$username = '';
$email = '';
$telefono = '';
$password = '';
$conferma_password = '';

function pulisciInput($value){
    $value = trim($value); //toglie spazi
    $value = strip_tags($value); //rimossi tag html e php
    $value = htmlentities($value); //converte caratteri speciali in entità html
    return $value;
}

if($_SERVER['REQUEST_METHOD']=="POST"){ //bottone submit premuto
    //controllo input 
    $nome = pulisciInput($_POST['nome']);
    if(strlen($nome) == 0){
        $messaggiForm .= '<li>Nome non inserito</li>';
        $formValido = false;
    }

    $cognome = pulisciInput($_POST['cognome']);
    if(strlen($cognome) == 0){
        $messaggiForm .= '<li>Cognome non inserito</li>';
        $formValido = false;
    }

    $username = pulisciInput($_POST['username']);
    if(strlen($username) == 0){
        $messaggiForm .= '<li>Username non inserito</li>';
        $formValido = false;
    }

    $email = pulisciInput($_POST['email']);
    $email_filtred = filter_input(INPUT_POST, 'email', FILTER_VALIDATE_EMAIL);
    if(strlen($email) == 0){
        $messaggiForm .= '<li>Email non inserita</li>';
        $formValido = false;
    }
    else if(!$email_filtred){ //controlla che l'email sia scritta nel formato corretto
        $messaggiForm .= '<li>Email non valida</li>';
        $formValido = false;
    }

    $telefono = pulisciInput($_POST['tel']);
    if(strlen($telefono) == 0){
        $messaggiForm .= '<li>Numero di Telefono non inserito</li>';
        $formValido = false;
    }
    else if(!preg_match('/^\+?[0-9]{10,15}$/', $telefono)){
        $messaggiForm .= '<li>Numero di Telefono non valido</li>';
        $formValido = false;
    }

    $password = trim($_POST['password']); //non usa pulisciInput perchè potrebbe togliere caratteri importanti
    if(strlen($password) < 8){
        $messaggiForm .= '<li>La password deve avere almeno 8 caratteri</li>';
        $formValido = false;
    }

    $conferma_password = trim($_POST['conferma_password']);
    if(strlen($conferma_password) == 0){
        $messaggiForm .= '<li>Password di conferma non inserita</li>';
        $formValido = false;
    }

    if($password !== $conferma_password){
        $messaggiForm .= '<li>Password di conferma diversa dalla password inserita</li>';
        $formValido = false;
    }
    $password = password_hash($password, PASSWORD_DEFAULT); // Hash della password
    //se il form è valido provo a connettermi al database
    if($formValido){
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
        //controllo che l'email inserita non sia già stata utilizzata
        $sqlCheckEmail = "SELECT * FROM Cliente WHERE Email = :email";
        $stmt = $pdo->prepare($sqlCheckEmail);
        $stmt->bindParam(':email', $email, PDO::PARAM_STR);
        $stmt->execute();
        //controllo che l'username inserito non sia già stato utilizzato 
        $sqlCheckUsername = "SELECT * FROM Cliente WHERE Username = :username";
        $stmt2 = $pdo->prepare($sqlCheckUsername);
        $stmt2->bindParam(':username', $username, PDO::PARAM_STR);
        $stmt2->execute();
        //controllo che il telefono inserito non sia già stato utilizzato
        $sqlCheckTel = "SELECT * FROM Cliente WHERE Telefono = :telefono";
        $stmt3 = $pdo->prepare($sqlCheckTel);
        $stmt3->bindParam(':telefono', $telefono, PDO::PARAM_STR);
        $stmt3->execute();
        
        if ($stmt->rowCount() > 0) { //email già stata utilizzata
            $messaggiForm .= '<li>Email già utilizzata</li>';
            header("Location: registrazione.php");
            exit();
        }
        if ($stmt2->rowCount() > 0) { //username già stato utilizzato
            $messaggiForm .= '<li>Username già utilizzato</li>';
            header("Location: registrazione.php");
            exit();
        }
        if ($stmt3->rowCount() > 0) { //telefono già stato utilizzato
            $messaggiForm .= '<li>Telefono già utilizzato</li>';
            header("Location: registrazione.php");
            exit();
        }
        //email, username e telefono non ancora registrati
        //registro dati su db e reinderizzo alla pagina principale
        $sqlInsert = "INSERT INTO Cliente (Nome,Cognome,Email,Telefono,Username,Pass) 
                        VALUES (:nome, :cognome, :email, :telefono, :username, :password)";             
        $stmt = $pdo->prepare($sqlInsert);
        $stmt->bindParam(':nome', $nome, PDO::PARAM_STR);
        $stmt->bindParam(':cognome', $cognome, PDO::PARAM_STR);
        $stmt->bindParam(':email', $email, PDO::PARAM_STR);
        $stmt->bindParam(':telefono', $telefono, PDO::PARAM_STR);
        $stmt->bindParam(':username', $username, PDO::PARAM_STR);
        $stmt->bindParam(':password', $password, PDO::PARAM_STR);
        if($stmt->execute()){
            $_SESSION['success_message'] = "Registrazione avvenuta con successo!";
            header("Location: index.php");
            exit();
        }
        else { 
            $_SESSION['error_message'] = "Abbiamo avuto un problema con la registrazione";
            header("Location: registrazione.php");
            exit();
        }
    }else{
        //faccio visualizzare i messaggi di errore del form
        header("Location: registrazione.php");
        $messaggiForm = '<div id = "messageErrors"><ul>'. $messaggiForm. '</ul></div>';
        $paginaHTML= str_replace("<messaggiForm />", $messaggiForm, $paginaHTML);
        exit();
    }
}

$DOM = file_get_contents("html/registrazione.html");
echo($DOM);
?>