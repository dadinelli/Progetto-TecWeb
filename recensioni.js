let currentPage = 1; 
let totalPages = 1;

// Funzione per caricare le recensioni
function caricaRecensioni() {
    fetch(`visualizza_recensioni.php?page=${currentPage}`)
        .then(response => response.json())
        .then(data => {
            const recensioniUL = document.getElementById('recensioni-container');
            recensioniUL.innerHTML = ''; 

            if (data.isLoggedIn && data.userReview) {
                const userReviewElem = document.createElement('div');
                userReviewElem.classList.add('recensione');
                userReviewElem.innerHTML = `
                    <div class="recensione-header">
                        <span class="nome-utente">La tua recensione</span>
                        <span class="data-recensione">${data.userReview.Data}</span>
                    </div>
                    <div class="recensione-body">
                        <p class="valutazione">Valutazione: ${data.userReview.Valutazione}/5</p>
                        <p class="testo-recensione">${data.userReview.Testo}</p>
                    </div>
                `;
                recensioniUL.appendChild(userReviewElem);
            }

            if (data.recentReviews && data.recentReviews.length > 0) {
                data.recentReviews.forEach(recensione => {
                    const recensioneElem = document.createElement('div');
                    recensioneElem.classList.add('recensione');
                    recensioneElem.innerHTML = `
                        <div class="recensione-header">
                            <span class="nome-utente">${recensione.Nome} ${recensione.Cognome}</span>
                            <span class="data-recensione">${recensione.Data}</span>
                        </div>
                        <div class="recensione-body">
                            <p class="valutazione">Valutazione: ${recensione.Valutazione}/5</p>
                            <p class="testo-recensione">${recensione.Testo}</p>
                        </div>
                    `;
                    recensioniUL.appendChild(recensioneElem);
                });
            }

            totalPages = data.totalPages;
            document.getElementById('load-more-reviews').style.display = currentPage < totalPages ? 'block' : 'none';
            document.getElementById('load-less-reviews').style.display = currentPage > 1 ? 'block' : 'none';
        })
        .catch(error => {
            console.error("Errore nel caricamento delle recensioni:", error);
        });
}

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

window.onload = caricaRecensioni;
