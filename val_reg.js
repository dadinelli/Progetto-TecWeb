// Gestione del menu a tendina
/* const navToggle = document.getElementById('hamb-button');
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
} */

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

let reg_form = document.getElementById('reg-form');

reg_form.addEventListener('submit', function (e) {

    e.preventDefault();
    clearError();
    nome = document.getElementById("nome");
    if(nome === ''){
        showError("nome-error", "Inserisci un nome");
        return false;
    }

    this.submit();
});


