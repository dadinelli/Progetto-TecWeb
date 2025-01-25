<?php
session_start();

$DOM = file_get_contents("html/private.html");

//Load user data
if ($_SESSION['is_logged_in'] === true) {
    $idCliente = $_SESSION['ID_Cliente'];

    $host = 'localhost';           
    //$port = '3306';               
    $dbname = 'progettotecweb';   
    $userdbname = 'root';          
    $passwordDB = '';     
    try {
        $dsn = "mysql:host=$host;dbname=$dbname;charset=utf8mb4";
        $pdo = new PDO($dsn, $userdbname, $passwordDB);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        
        $stmt = $pdo->prepare("SELECT * FROM Cliente WHERE ID_Cliente = :idCliente");
        $stmt->bindParam(':idCliente', $idCliente, PDO::PARAM_INT);
        $stmt->execute();

        if ($stmt->rowCount() > 0) {
            $cliente = $stmt->fetch(PDO::FETCH_ASSOC);

            $nome = $cliente['Nome'];
            $cognome = $cliente['Cognome'];
            $email = $cliente['Email'];
            $telefono = $cliente['Telefono'];
            $username = $cliente['Username'];
            $_SESSION['nome'] = $nome;
            $_SESSION['email'] = $email;
            $_SESSION['telefono'] = $telefono;
            $_SESSION['Username'] = $username;
            $_SESSION['Cognome'] = $cognome;

            $show_data = "
            <form id='login-window' name='change-data-form' action='save_new_data.php' method='POST'>
                <h1>Modifica dati utente</h1>

                <fieldset id='personal-info'>
                    <legend>Informazioni personali</legend>
                    <label for='email'>Email</label>
                    <input id='email' name='email' type='email' placeholder='$email' mandatory required onblur=''>
                    <p class='error email-error'></p>
                    <label for='tel'>Numero di Telefono</label>
                    <input id='tel' name='tel' type='tel' placeholder='$telefono' mandatory required onblur=''>
                    <p class='error tel-error'></p>
                </fieldset>

                <fieldset id='user-info'>
                    <legend>Account</legend>
                    <label for='username'>Username</label>
                    <input id='username' name='username' type='username' placeholder='$username' mandatory required onblur=''>
                    <label for='password-attuale'>Password attuale</label>
                    <input id='password-attuale' name='password-attuale' type='password' placeholder='Password attuale' mandatory required onblur=''>
                    <p class='error actual-password-error'></p>
                    <label for='nuova-password'>Nuova password</label>
                    <input id='nuova-password' name='nuova-password' type='password' placeholder='Nuova password' mandatory required onblur=''>
                    <p class='error new-password-error'></p>
                    <label for='confirm-new-password'>Conferma nuova password</label>
                    <input id='confirm-new-password' name='confirm-new-password' type='password' placeholder='Conferma nuova password' mandatory required onblur=''>
                    <p class='error confirm-password-error'></p>
                </fieldset>

                <button class='login-button' type='submit' onkeydown='enter'>Salva</button>
            </form>
            ";

            $DOM = str_replace('<div id="content"></div>', $show_data, $DOM);

        } else {
            echo "Cliente non trovato. $idCliente";
        }
    } catch (PDOException $e) {
        echo "Errore di connessione: " . $e->getMessage();
    }
} else {
    echo "Utente non loggato o ID Cliente non trovato nella sessione.";
    header("Location: area-riservata.php");
}

/*include "prenota.php";
if(isset($_POST['submit'])){
    reservation($DOM);
}*/

echo($DOM);
?>