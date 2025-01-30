let currentPage = 1;
let totalPages = 1;

// Carica le recensioni
function caricaRecensioni() {
    fetch(`visualizza_recensioni.php?page=${currentPage}`)
        .then(response => response.json())
        .then(data => {
            const container = document.getElementById('recensioni-container');
            container.innerHTML = '';

            /*
             Mostra o nasconde il form di inserimento recensione
             Se l'utente è loggato e HA una recensione (data.userReview != null),
             allora nascondi il form; altrimenti, mostralo.
            */
            if (data.isLoggedIn && data.userReview) {
                // Utente loggato con recensione -> nascondi form
                document.getElementById('login-window').style.display = 'none';
            } else {
                // Utente non loggato OPPURE utente loggato senza recensione
                // -> mostra form
                document.getElementById('login-window').style.display = 'block';
            }

            // Se l'utente è loggato e ha una recensione personale, mostriamola
            if (data.isLoggedIn && data.userReview) {
                const userReviewElem = creaElementoRecensione(data.userReview, true);
                container.appendChild(userReviewElem);
            }

            // Aggiunge le recensioni "recenti"
            if (data.recentReviews && data.recentReviews.length > 0) {
                data.recentReviews.forEach(rec => {
                    const recensioneElem = creaElementoRecensione(rec, false);
                    container.appendChild(recensioneElem);
                });
            }
            totalPages = data.totalPages;
            document.getElementById('load-more-reviews').style.display = (currentPage < totalPages) ? 'block' : 'none';
            document.getElementById('load-less-reviews').style.display = (currentPage > 1) ? 'block' : 'none';
        })
        .catch(error => console.error("Errore nel caricamento delle recensioni:", error));
}

// Crea un singolo elemento DOM recensione
function creaElementoRecensione(recensione, isUserReview) {
    const recensioneElem = document.createElement('div');
    recensioneElem.classList.add('recensione');

    const header = document.createElement('div');
    header.classList.add('recensione-header');

    const spanNome = document.createElement('span');
    spanNome.classList.add('nome-utente');

    if (recensione.Nome && recensione.Cognome) {
        spanNome.textContent = `${recensione.Nome} ${recensione.Cognome}`;
    } else {
        spanNome.textContent = isUserReview ? "La tua recensione" : "Utente Anonimo";
    }

    const spanData = document.createElement('span');
    spanData.classList.add('data-recensione');
    spanData.textContent = recensione.Data || '';

    header.appendChild(spanNome);
    header.appendChild(spanData);

    const body = document.createElement('div');
    body.classList.add('recensione-body');

    const pValutazione = document.createElement('p');
    pValutazione.classList.add('valutazione');
    pValutazione.textContent = `Valutazione: ${recensione.Valutazione}/5`;

    const pTesto = document.createElement('p');
    pTesto.classList.add('testo-recensione');
    pTesto.textContent = recensione.Testo;

    body.appendChild(pValutazione);
    body.appendChild(pTesto);

    // Se è la recensione dell'utente loggato, aggiungi pulsanti Modifica/Elimina
    if (isUserReview) {
        const btnModifica = document.createElement('button');
        btnModifica.classList.add('modifica-button');
        btnModifica.textContent = "Modifica";
        btnModifica.addEventListener('click', () => {
            mostraFormModifica(recensioneElem, recensione);
        });

        const btnElimina = document.createElement('button');
        btnElimina.classList.add('elimina-button');
        btnElimina.textContent = "Elimina";
        btnElimina.addEventListener('click', () => {
            eliminaRecensione(recensione.ID_Recensione);
        });

        body.appendChild(btnModifica);
        body.appendChild(btnElimina);
    }

    recensioneElem.appendChild(header);
    recensioneElem.appendChild(body);

    return recensioneElem;
}

// Mostra un form inline per modificare la recensione
function mostraFormModifica(recensioneElem, recensione) {
    // Trova la recensione-body e la svuota
    const body = recensioneElem.querySelector('.recensione-body');
    body.innerHTML = '';

    // Crea un <form> 
    const form = document.createElement('form');
    form.classList.add('edit-review-form');   

    // Label voto
    const labelVoto = document.createElement('label');
    labelVoto.textContent = "Voto:";
    labelVoto.classList.add('edit-review-label'); 
    // Select voto
    const selectVoto = document.createElement('select');
    selectVoto.classList.add('edit-review-select'); 

    for (let i = 1; i <= 5; i++) {
        const opt = document.createElement('option');
        opt.value = i;
        opt.textContent = i;
        if (i == recensione.Valutazione) opt.selected = true;
        selectVoto.appendChild(opt);
    }

    // Label testo
    const labelTesto = document.createElement('label');
    labelTesto.textContent = "Recensione:";
    labelTesto.classList.add('edit-review-label');

    // Textarea
    const textarea = document.createElement('textarea');
    textarea.rows = 3;
    textarea.value = recensione.Testo;
    textarea.classList.add('edit-review-textarea'); 

    // Bottone Salva
    const btnSalva = document.createElement('button');
    btnSalva.textContent = "Salva";
    btnSalva.type = "submit";
    btnSalva.classList.add('edit-review-btn'); 

    // Bottone Annulla
    const btnAnnulla = document.createElement('button');
    btnAnnulla.textContent = "Annulla";
    btnAnnulla.type = "button";
    btnAnnulla.classList.add('edit-review-btn');

    // Eventi
    form.addEventListener('submit', (e) => {
        e.preventDefault();
        salvaModificaRecensione(recensione.ID_Recensione, selectVoto.value, textarea.value);
    });
    btnAnnulla.addEventListener('click', () => {
        caricaRecensioni();
    });

    // Assembla il form
    form.appendChild(labelVoto);
    form.appendChild(selectVoto);
    form.appendChild(document.createElement('br'));

    form.appendChild(labelTesto);
    form.appendChild(textarea);
    form.appendChild(document.createElement('br'));

    form.appendChild(btnSalva);
    form.appendChild(btnAnnulla);

    body.appendChild(form);
}


// Salva le modifiche (POST)
function salvaModificaRecensione(idRecensione, voto, testo) {
    fetch('modifica_recensione.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
        body: new URLSearchParams({
            id: idRecensione,
            voto: voto,
            testo: testo
        })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            alert(data.message);
            caricaRecensioni(); 
        } else {
            alert(data.message || "Errore durante la modifica");
        }
    })
    .catch(err => console.error("Errore modifica:", err));
}

// Elimina la recensione
function eliminaRecensione(idRecensione) {
    if (!confirm("Sei sicuro di voler eliminare questa recensione?")) return;
    fetch(`elimina_recensione.php?id=${idRecensione}`)
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            alert(data.message);
            // Dopo l'eliminazione, ricarico le recensioni:
            // userReview diventerà null e il form tornerà visibile
            caricaRecensioni();
        } else {
            alert(data.message || "Errore durante l'eliminazione");
        }
    })
    .catch(err => console.error("Errore eliminazione:", err));
}

// Navigazione tra le pagine
function caricaAltreRecensioni() {
    if (currentPage < totalPages) {
        currentPage++;
        caricaRecensioni();
    }
}

function tornaIndietro() {
    if (currentPage > 1) {
        currentPage--;
        caricaRecensioni();
    }
}

// Avvia
window.addEventListener('load', caricaRecensioni);
