<?php 
    session_start();
    if (isset($_SESSION["is_logged_in"]) == true){
        $valutazione = $_POST['voto'];
        $recensione = '';
        $user_ID = $_SESSION['ID_Cliente'];
        $currentDate = date("Y-m-d");
        $formValido = true;


        function pulisciInput($value){
            $value = trim($value); //toglie spazi
            $value = strip_tags($value); //rimossi tag html e php
            $value = htmlentities($value); //converte caratteri speciali in entità html
            return $value;
        }

        if($_SERVER['REQUEST_METHOD']==="POST"){ //bottone submit premuto
            //controllo input 
            $recensione = pulisciInput($_POST['recensione']);
            if(strlen($recensione) == 0){;
                $formValido = false;
            }
        
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
                $sqlInsert = "INSERT INTO Recensione (Valutazione,Testo,Data,ID_Cliente) 
                            VALUES (:valutazione, :testo, :data, :id_cliente)";
                $stmt = $pdo->prepare($sqlInsert);
                $stmt->bindParam(':valutazione', $valutazione, PDO::PARAM_INT);
                $stmt->bindParam(':testo', $recensione, PDO::PARAM_STR);
                $stmt->bindParam(':data', $currentDate, PDO::PARAM_STR);
                $stmt->bindParam(':id_cliente', $user_ID, PDO::PARAM_INT);
                
                if($stmt->execute()){
                    echo "Recensione realizzata con successo.";
                    header("Location: index.html");
                    exit();
                }
                else {
                    echo "Problemi con il salvataggio della recensione.";
                    header("Location: index.html");
                    exit();
                }
            }
            else {
                echo "Form non valido";
                header("Location: index.html");
                exit();
            }
        }
    }
    else {
        echo "Effettuare il login";
        header("Location: index.html");
        exit();
    }
?>
