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

reg_form.addEventListener('submit', function (e) {

    e.preventDefault();
    clearError();
    
    let name = document.getElementById('nome').value;

    console.log(name.length);

    if(name.length > 30){
        showError("name-error", "Inserisci un nome valido");
        return false;
    }

    let surname = document.getElementById('cognome').value;
    if(surname.length > 30){
        showError("surame-error", "Inserisci un cognome valido");
        return false;
    }

    let pwd = document.getElementById('password').value;
    let confirm_pwd = document.getElementById('confirm-password').value;

    if(pwd !== confirm_pwd){
        showError("confirm-password-error", "Le password non corrispondono");
        return false;
    }

    this.submit();
});


