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

// Validazione del form e feedback visivo
const form = document.getElementById('general-contact-form');

if(form){
    form.addEventListener('submit', function (e) {
        const name = document.getElementById('general-name').value.trim();
        const email = document.getElementById('general-email').value.trim();
        const message = document.getElementById('general-message').value.trim();
    
        if (!name || !email || !message) {
            e.preventDefault(); // Impedisce l'invio del form
            alert("Per favore compila tutti i campi.");
            return;
        }
    
        // Feedback visivo (mostra un messaggio di conferma)
        e.preventDefault(); // Rimuovi questa linea se hai un backend per gestire il form
        document.getElementById('confirmation-message').style.display = 'block';
        form.reset(); // Resetta i campi del form
    });
}

document.querySelectorAll('.menu-item').forEach(item => {
    item.addEventListener('mouseover', () => {
        item.classList.add('hovered');
    });
    item.addEventListener('mouseout', () => {
        item.classList.remove('hovered');
    });

    item.addEventListener('click', () => {
        // Aggiungi l'effetto di selezione (per far capire che è stato cliccato)
        item.classList.toggle('selected');
    });
});


