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

edit_form = document.getElementById('login-window');
let passwordError = false;

edit_form.addEventListener('change', function (e) {

    clearError();

    let password = document.getElementById('nuova-password').value.trim();
    let confirmPassword = document.getElementById('confirm-new-password').value.trim();

    if(password != confirmPassword){
        showError("confirm-password-error", "Le password non corrispondono");
        passwordError = true;
    }
    else passwordError = false;
});

reg_form.addEventListener('submit', function (e) {

    e.preventDefault();
    if(!passwordError){
        this.submit();
    }
});
