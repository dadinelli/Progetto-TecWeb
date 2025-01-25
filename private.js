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

/*function loadPrivateContent() {
    const xhr = new XMLHttpRequest();                                       //se non sapete cosa è, praticametne è un oggetto che serve per fare richieste http asincrone al server
                                                                            //quindi non bisogna ricaricare la pagina
    xhr.open('GET', 'private.php', true);                                   //chiede al private.php di mandarti dei dati (GET) e true serve per indicare che la richiesta è asincrona
    xhr.onload = function () {                                              //callback eseguita automaticamente quando è arrivata la risposta del server
        if (xhr.status === 200) {                                           //richiesta soddistatta con il numero 200
            document.getElementById('content').innerHTML = xhr.responseText;//prende i dati (xhr.responseText) e lo setta nel document. blablabla e questo è collegato al <div id="content">
        }
    };
    xhr.send();                                                             //Dopo aver aperto la richiesta, questa riga la invia effettivamente al server.
                                                                            //A questo punto, il browser invia una richiesta GET al server chiedendo il contenuto di private.php.
}
loadPrivateContent();*/


