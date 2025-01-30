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
email_error = false;
tel_error = false;
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
            showError("email-error", "Inserisci un email valida");
            email_error = true;
        }
        else email_error = false;
    }

    //validazione telefono
    let tel = document.getElementById('tel').value.trim();
    const telPattern = /^\+39\s?(\(?\d{3}\)?[\s\-]?)?\d{3}[\s\-]?\d{4}$|^\(?\d{3}\)?[\s\-]?\d{3}[\s\-]?\d{4}$/;
    if(tel.length > 0){
        if(!telPattern.test(tel)){
            showError("tel-error", "Inserisci un numero di telefono valido");
            tel_error = true;
        }
        else tel_error = false;
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
    if(!(name1_error || name2_error || surname1_error || surname2_error || email_error || tel_error || pass_error ||conf_pass_error)){
        this.submit();
    }
});

