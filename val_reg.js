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


//input validation
function showError(error_id, error_message){
    document.querySelector("."+error_id).classList.add("display-error");
    document.querySelector("."+error_id).innerHTML = error_message;
}

function clearError(){
    let errors = document.querySelectorAll(".error");
    for(let error of errors){
        error.classList.remove("display-error");
    }
}

let reg_form = document.getElementById('login-window');
name1_error = false;
name2_error = false;
surname1_error = false;
surname1_error = false;
email1_error = false;
email2_error = false;
tel1_error = false;
tel2_error = false;
user_error = false;
pass_error = false;
conf_pass_error = false;

reg_form.addEventListener('change', function (e) {
    
    clearError();
    
    //validazione nome
    let nome = document.getElementById('nome').value.trim();
    if(nome.length > 0){    
        if(nome.length > 30 || nome.length < 2){
            showError("name1-error", "Inserisci un nome valido");
            name1_error = true;
        }
        else name1_error = false;
        if(/\d/.test(nome)){
            showError("name2-error", "Nome non può contenere numeri");
            name2_error = true;
        }
        else name2_error = false;
    }

    //validazione cognome
    let cognome = document.getElementById('cognome').value.trim();
    if(cognome.length > 0){
        if(cognome.length > 30 || cognome.length < 2){
            showError("surname1-error", "Inserisci un cognome valido");
            surname1_error = true;
        }
        else surname1_error = false;
        if(/\d/.test(cognome)){
            showError("surname2-error", "Cognome non può contenere numeri");
            surname2_error = true;
        }
        else surname2_error = false;
    }

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
                email2_error = true;
            }
            else if(!data.result){
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
                showError("username-error", "Username già registrato");
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

    //validazione password
    let password = document.getElementById('password').value.trim();
    if(password.length > 0){
        if(password.length < 8){
            showError("password-error", "Password deve avere almeno 8 caratteri");
            pass_error = true;
        }
        else pass_error = false;
    }

    //validazione conferma password
    let confirm_pwd = document.getElementById('confirm-password').value.trim();
    if(confirm_pwd.length > 0){
        if(password !== confirm_pwd){
            showError("confirm-password-error", "Le password non corrispondono");
            conf_pass_error = true;
        }
        else conf_pass_error = false;
    }
});

reg_form.addEventListener('submit', function (e) {

    e.preventDefault();
    if(!(name1_error || name2_error || surname1_error || surname2_error || email1_error || email2_error || tel1_error || tel2_error || user_error || pass_error || conf_pass_error)){
        this.submit();
    }
});

