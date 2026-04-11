document.addEventListener("DOMContentLoaded", () => {
    fetchPosts();

    // Event listener delegato per i bottoni "Partecipa"
    const container = document.getElementById('posts-container');
    if (container) {
        container.addEventListener('click', event => {
            const btn = event.target.closest('[data-post-id]');
            if (btn) {
                event.preventDefault();
                const postId = parseInt(btn.dataset.postId);
                partecipaPost(postId);
            }
        });
    }
});

function fetchPosts() {
    const hasFilters = window.currentFilters && Object.keys(window.currentFilters).length > 0;
    const apiUrl = hasFilters ? "../ajax/posts/api-posts-filter.php" : "../ajax/posts/api-posts.php";

    let url = apiUrl;
    if (hasFilters) {
        const params = new URLSearchParams();
        if (window.currentFilters.sort) params.set('sort', window.currentFilters.sort);
        if (window.currentFilters.subject) params.set('subject', window.currentFilters.subject);
        if (window.currentFilters.type) params.set('type', window.currentFilters.type);
        if (window.currentFilters.date_from) params.set('date_from', window.currentFilters.date_from);
        if (window.currentFilters.no_auth) params.set('no_auth', '1');
        if (window.currentFilters.show_unavailable) params.set('show_unavailable', '1');
        url += '?' + params.toString();
    }

    fetch(url)
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            renderPosts(data.posts);
        } else {
            console.error("Errore nel caricamento dei post:", data.message);
        }
    })
    .catch(error => {
        console.error("Errore durante la fetch dei post:", error);
    });
}

function renderPosts(apiDataArray) {
    const container = document.getElementById('posts-container');
    if (!container) return;

    container.innerHTML = '';
    const badge = document.getElementById('posts-count-badge');

    if (apiDataArray.length === 0) {
        container.innerHTML = '<p class="text-muted text-center">Nessun post trovato.</p>';
        if (badge) badge.textContent = '0 Trovati';
        return;
    }

    if (badge) badge.textContent = apiDataArray.length + ' Trovati';

    apiDataArray.forEach(post => {
        container.insertAdjacentHTML('beforeend', createPostCard(post));
    });

    const tooltipTriggerList = document.querySelectorAll('[data-bs-toggle="tooltip"]');
    [...tooltipTriggerList].map(tooltipTriggerEl => new bootstrap.Tooltip(tooltipTriggerEl));
}

function createPostCard(post) {
    const isProgetto = post.tipo === 'progettuale';
    const iconClass = isProgetto ? 'bi-diagram-3' : 'bi-book';
    const tooltipText = isProgetto ? 'Progetto di gruppo' : 'Sessione di studio';

    const necessitaApprovazione = parseInt(post.richiede_approvazione, 10) === 1;
    const btnClass = necessitaApprovazione ? 'btn-custom-secondary' : 'btn-custom-primary';
    const btnText = necessitaApprovazione ? 'Invia richiesta' : 'Partecipa';

    const formatDate = (dateString) => {
        if (!dateString) return 'N/A';
        const date = new Date(dateString);
        return date.toLocaleDateString('it-IT', {
            day: '2-digit',
            month: '2-digit',
            year: 'numeric'
        });
    };

    const formatDateTime = (dateString) => {
        if (!dateString) return 'N/A';
        const date = new Date(dateString);
        if (isNaN(date.getTime())) return 'Data non valida';
        
        return date.toLocaleString('it-IT', {
            day: '2-digit',
            month: '2-digit',
            year: 'numeric',
            hour: '2-digit',
            minute: '2-digit'
        });
    };

    const dataInizio = formatDate(post.data_inizio);
    const dataFine = formatDate(post.data_fine);
    const dataPubblicazione = formatDateTime(post.data_creazione);

    return `
        <div class="card shadow-sm mb-4 border-0">
            <div class="card-body p-4">

                <div class="d-flex justify-content-between align-items-start mb-2">
                    <h3 class="h5 fw-bold mb-0 text-dark">${post.titolo}</h3>
                    <span role="img" aria-label="${tooltipText}">
                        <em class="bi ${iconClass} fs-4" style="color: var(--color-primary);" data-bs-toggle="tooltip" data-bs-placement="left" title="${tooltipText}" aria-hidden="true"></em>
                    </span>
                </div>

                <div class="d-flex align-items-center text-muted small mb-3">
                    <em class="bi bi-person-circle fs-5 me-2"></em>
                    <span>Pubblicato da <strong>${post.creatore_nome} ${post.creatore_cognome}</strong></span>
                </div>

                <hr class="my-3 text-secondary opacity-25" />

                <div class="row g-3 mb-4">
                    <div class="col-12 col-sm-6">
                        <div class="d-flex align-items-center text-secondary small">
                            <em class="bi bi-tag me-2" aria-hidden="true"></em>
                            <strong>Materia:</strong>&nbsp;${post.materia_nome}
                        </div>
                    </div>
                    <div class="col-12 col-sm-6">
                        <div class="d-flex align-items-center text-secondary small">
                            <em class="bi bi-people me-2" aria-hidden="true"></em>
                            <strong>Partecipanti:</strong>&nbsp;${parseInt(post.partecipanti_attuali) || 0}/${parseInt(post.max_partecipanti)}
                        </div>
                    </div>
                    <div class="col-12 col-sm-6">
                        <div class="d-flex align-items-center text-secondary small">
                            <em class="bi bi-calendar-event me-2" aria-hidden="true"></em>
                            <strong>Dal:</strong>&nbsp;${dataInizio} &ndash; <strong>Al:</strong>&nbsp;${dataFine}
                        </div>
                    </div>
                    <div class="col-12 col-sm-6">
                        <div class="d-flex align-items-center text-secondary small">
                            <em class="bi bi-geo-alt me-2" aria-hidden="true"></em>
                            <strong>Luogo:</strong>&nbsp;${post.luogo || 'Non specificato'}
                        </div>
                    </div>
                </div>

                <div class="d-flex flex-wrap justify-content-between align-items-end mt-2">
                    <div class="row gx-3 mb-2 mb-sm-0 w-100 align-items-center">
                        <div class="col-8 col-sm-6 col-md-5 col-xl-4">
                            <a href="#" class="btn ${btnClass} w-100 text-center fw-semibold" data-post-id="${parseInt(post.id)}">
                                ${btnText}
                            </a>
                        </div>
                        <div class="col-auto">
                            <a href="post_info.php?id=${parseInt(post.id)}" class="btn btn-link text-secondary p-0 border-0" aria-label="Informazioni aggiuntive" data-bs-toggle="tooltip" data-bs-placement="top" title="Maggiori dettagli">
                                <em class="bi bi-info-circle fs-4" aria-hidden="true"></em>
                            </a>
                        </div>
                    </div>
                    <div class="text-secondary small mt-3 mt-sm-0 w-100 text-end">
                        <em class="bi bi-clock me-1" aria-hidden="true"></em>Pubblicato il ${dataPubblicazione}
                    </div>
                </div>

            </div>
        </div>
    `;
}

function partecipaPost(postId) {
    fetch('../ajax/posts/api-partecipa.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({ post_id: postId })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            alert('Partecipazione avvenuta con successo!');
            location.reload();
        } else {
            alert(data.message || 'Errore durante la partecipazione.');
        }
    })
    .catch(error => {
        console.error('Errore durante la partecipazione:', error);
        alert('Errore di connessione. Riprova.');
    });
}