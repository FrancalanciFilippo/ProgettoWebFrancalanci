document.addEventListener("DOMContentLoaded", () => {
    fetchMyPosts();
});

function fetchMyPosts() {
    fetch('../ajax/posts/api-my-posts.php')
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                renderMyPosts(data.posts);
            } else {
                console.error("Errore nel caricamento dei miei post:", data.message);
            }
        })
        .catch(error => {
            console.error("Errore durante la fetch dei miei post:", error);
        });
}

function renderMyPosts(posts) {
    const container = document.getElementById('my-posts-list');
    if (!container) {
        console.error("Container #my-posts-list non trovato!");
        return;
    }

    container.innerHTML = '';

    if (!posts || posts.length === 0) {
        return;
    }

    posts.forEach(post => {
        container.insertAdjacentHTML('beforeend', createMyPostCard(post));
    });

    if (typeof bootstrap !== 'undefined') {
        const tooltipTriggerList = document.querySelectorAll('[data-bs-toggle="tooltip"]');
        [...tooltipTriggerList].map(el => new bootstrap.Tooltip(el));
    }
}

function createMyPostCard(post) {
    const isProgetto = post.tipo === 'progettuale';
    const iconClass = isProgetto ? 'bi-diagram-3' : 'bi-book';
    const tooltipText = isProgetto ? 'Progetto di gruppo' : 'Sessione di studio';

    const formatDate = (dateString) => {
        if (!dateString) return 'N/A';
        const date = new Date(dateString);
        return date.toLocaleDateString('it-IT', { day: '2-digit', month: 'short', year: 'numeric' });
    };

    const formatDateTime = (dateString) => {
        if (!dateString) return 'N/A';
        const date = new Date(dateString);
        if (isNaN(date.getTime())) return 'Data non valida';

        const day = String(date.getDate()).padStart(2, '0');
        const month = String(date.getMonth() + 1).padStart(2, '0');
        const year = date.getFullYear();
        const hours = String(date.getHours()).padStart(2, '0');
        const minutes = String(date.getMinutes()).padStart(2, '0');

        return `${day}/${month}/${year}, ${hours}:${minutes}`;
    };

    const dataInizio = formatDate(post.data_inizio);
    const dataFine = formatDate(post.data_fine);
    const dataPubblicazione = formatDateTime(post.data_creazione);

    const partecipanti = parseInt(post.partecipanti_attuali) || 0;
    const maxPartecipanti = parseInt(post.max_partecipanti) || 10;

    return `
        <div class="card shadow-sm mb-4 border-0">
            <div class="card-body p-4">
                <!-- Titolo e Icona Tipo -->
                <div class="d-flex justify-content-between align-items-start mb-2">
                    <h2 class="h5 fw-bold mb-0 text-dark">${escapeHtml(post.titolo)}</h2>
                    <em class="bi ${iconClass} fs-4" style="color: var(--color-primary);" 
                        data-bs-toggle="tooltip" data-bs-placement="left" title="${escapeHtml(tooltipText)}" aria-hidden="true"></em>
                </div>
                
                <!-- Autore (sempre "Tu" perché sono i miei post) -->
                <div class="d-flex align-items-center text-muted small mb-3">
                    <em class="bi bi-person-circle fs-5 me-2"></em>
                    <span>Pubblicato da <strong>Tu</strong></span>
                </div>

                <hr class="my-3 text-secondary opacity-25" />

                <!-- Dettagli -->
                <div class="row g-3 mb-4">
                    <div class="col-12 col-sm-6">
                        <div class="d-flex align-items-center text-secondary small">
                            <em class="bi bi-tag me-2" aria-hidden="true"></em>
                            <strong>Materia:</strong>&nbsp;${escapeHtml(post.materia_nome)}
                        </div>
                    </div>
                    <div class="col-12 col-sm-6">
                        <div class="d-flex align-items-center text-secondary small">
                            <em class="bi bi-people me-2" aria-hidden="true"></em>
                            <strong>Partecipanti:</strong>&nbsp;${partecipanti}/${maxPartecipanti}
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
                            <strong>Luogo:</strong>&nbsp;${escapeHtml(post.luogo || 'Non specificato')}
                        </div>
                    </div>
                </div>
                
                <!-- Descrizione e File -->
                <div class="p-3 bg-light rounded-3 mb-4">
                    <h3 class="h6 fw-bold mb-2">Descrizione</h3>
                    <p class="small text-secondary mb-0">${escapeHtml(post.post_descrizione || 'Nessuna descrizione')}</p>
                </div>

                <!-- Bottoni e Data -->
                <div class="d-flex flex-wrap justify-content-between align-items-end mt-2">
                    <div class="row gx-2 mb-2 mb-sm-0 w-100 align-items-center">
                        <div class="col-6 col-sm-5 col-md-4 col-xl-3">
                            <a href="edit_post.php?id=${post.id}" class="btn btn-custom-primary w-100 text-center fw-semibold">
                                <em class="bi bi-pencil-square me-1"></em>Modifica
                            </a>
                        </div>
                        <div class="col-6 col-sm-5 col-md-4 col-xl-3">
                            <button class="btn btn-outline-danger w-100 text-center fw-semibold" 
                                    onclick="confirmDeletePost(${post.id}, '${escapeHtml(post.titolo)}')">
                                <em class="bi bi-trash me-1"></em>Elimina
                            </button>
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

function escapeHtml(text) {
    if (!text) return '';
    const div = document.createElement('div');
    div.textContent = text;
    return div.innerHTML;
}

function confirmDeletePost(postId, postTitle) {
    if (!confirm('Sei sicuro di voler eliminare il post "' + postTitle + '"?\n\nL\'azione e irreversibile.')) {
        return;
    }

    fetch('../ajax/posts/api-delete-post.php?id=' + postId)
        .then(response => response.json())
        .then(result => {
            if (result.success) {
                alert(result.message);
                window.location.href = result.redirect || 'my_posts.php';
            } else {
                alert('Errore: ' + result.message);
            }
        })
        .catch(error => {
            console.error('Errore eliminazione:', error);
            alert('Errore di connessione. Riprova.');
        });
}