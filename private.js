// Gestione del menu a tendina
const navToggle = document.getElementById('hamb-button');
const menuList = document.getElementById('menu-list');

navToggle.addEventListener('click', () => {
    menuList.classList.toggle('visible');
});

document.addEventListener('click', (event) => {
    if (!menuList.contains(event.target) && !navToggle.contains(event.target)) {
        menuList.classList.remove('visible');
    }
});

//Verifica data dopo  il giorno corrente
function checkDate(){
    const date = document.getElementById('date').value;
    const today = new Date().toISOString().split('T')[0];

    if(date < today) return true;
    else return false;
}

function showError(error_id, error_message){
    document.querySelector("."+error_id).classList.add("display-error");
    document.querySelector("."+error_id).innerHTML = error_message;
}

function showSuccess(success_id, success_mex){
    document.querySelector("."+success_id).classList.add("display-success");
    document.querySelector("."+success_id).innerHTML = success_mex;
}

function clearError(){
    let errors = document.querySelectorAll(".error");
    for(let error of errors){
        error.classList.remove("display-error");
    }
}

let reservation_form = document.getElementById('reservation-form');
let date_error = true;
let people_number_error = true;
let time_error = true;

reservation_form.addEventListener('change', function (e) {

    clearError();

    if(checkDate()){
        showError("date-error", "Inserisci una data futura");
    }
    else date_error = false;

    let peopleNumber = document.getElementById('numero-persone').value;
    if(peopleNumber > 20){
        console.log("entra");
        showError("too-many-people", "Per gruppi superiori a 20 persone contattare direttamente il ristorante");
    }
    else people_number_error = false;
});

reservation_form.addEventListener('submit', function(e){
    
    e.preventDefault();
    if(date_error === false && people_number_error === false){
        this.submit();
        showSuccess("success", "Prenotazione inviata correttamente!")
        console.log("successo");
    }
});

edit_form = document.getElementById('change-data-form');

email1_error = false;
email2_error = false;
tel1_error = false;
tel2_error = false;
user_error = false;
actual_pass_error = false;
new_pass_error = false;
conf_pass_error = false;

edit_form.addEventListener('change', function (e) {
    
    clearError();

    //validazione email
    let email = document.getElementById('email').value.trim();
    const emailPattern = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    if(email.length > 0){
        if(!emailPattern.test(email)){
            showError("email1-error", "Inserisci un email valida");
            email1_error = true;
        }
        else email1_error = false;
        fetch("CheckEmailSaved.php", {
            method: "POST",
            headers: {
                "Content-Type": "application/x-www-form-urlencoded" 
            },
            body: new URLSearchParams({ checkEmail: email }) 
        })
        .then(response => response.json()) 
        .then(data => {
            if(data.result){
                showError("email2-error", "Email già registrata");
                email1_error = true;
            }
            else if(!data.result) {
                email2_error = false;
            }
        })
        .catch(error => {
            console.error("Errore nella richiesta:", error);
        });
    }

    //validazione telefono
    let tel = document.getElementById('tel').value.trim();
    const telPattern = /^\+39\s?(\(?\d{3}\)?[\s\-]?)?\d{3}[\s\-]?\d{4}$|^\(?\d{3}\)?[\s\-]?\d{3}[\s\-]?\d{4}$/;
    if(tel.length > 0){
        if(!telPattern.test(tel)){
            showError("tel1-error", "Inserisci un numero di telefono valido");
            tel1_error = true;
        }
        else tel1_error = false;
        fetch("CheckTelSaved.php", {
            method: "POST",
            headers: {
                "Content-Type": "application/x-www-form-urlencoded" 
            },
            body: new URLSearchParams({ checkTel: tel }) 
        })
        .then(response => response.json()) 
        .then(data => {
            if(data.result){
                showError("tel2-error", "Telefono già registrato");
                tel2_error = true;
            }
            else if(!data.result) {
                tel2_error = false;
            }
        })
        .catch(error => {
            console.error("Errore nella richiesta:", error);
        });
    }
    
    //validazione user
    let user = document.getElementById('username').value.trim();
    if(user.length > 0){
        fetch("CheckUserSaved.php", {
            method: "POST",
            headers: {
                "Content-Type": "application/x-www-form-urlencoded" 
            },
            body: new URLSearchParams({ checkUser: user }) 
        })
        .then(response => response.json()) 
        .then(data => {
            if(data.result){
                showError("user-error", "Username già registrato");
                user_error = true;
            }
            else if(!data.result){
                user_error = false;
            }
        })
        .catch(error => {
            console.error("Errore nella richiesta:", error);
        });
    }

    //validazione vecchia password
    let actual_password = document.getElementById('password-attuale').value.trim();
    if(actual_password.length > 0){
        fetch("CheckPassSaved.php", {
            method: "POST",
            headers: {
                "Content-Type": "application/x-www-form-urlencoded" 
            },
            body: new URLSearchParams({ checkPass: actual_password }) 
        })
        .then(response => response.json()) 
        .then(data => {
            if(data.result){
                showError("actual-password-error", "Password errata");
                actual_pass_error = true;
            }
            else if(!data.result){
                actual_pass_error = false;
            }
        })
        .catch(error => {
            console.error("Errore nella richiesta:", error);
        });
    }

    //validazione nuova password
    let new_password = document.getElementById('nuova-password').value.trim();
    if(new_password.length > 0){
        if(new_password.length < 4){
            showError("new-password-error", "Password deve avere almeno 4 caratteri");
            new_pass_error = true;
        }
        else new_pass_error = false;
    }

    //validazione conferma nuova password
    let confirm_pwd = document.getElementById('confirm-new-password').value.trim();
    if(confirm_pwd.length > 0){
        if(new_password !== confirm_pwd){
            showError("confirm-password-error", "Le password non corrispondono");
            conf_pass_error = true;
        }
        else conf_pass_error = false;
    }
});

edit_form.addEventListener('submit', function (e) {

    e.preventDefault();
    if(!(email1_error || email2_error || tel1_error || tel2_error || user_error || actual_pass_error || new_pass_error || conf_pass_error)){
        this.submit();
    }
});
