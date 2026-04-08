document.addEventListener("DOMContentLoaded", () => {
    fetchJoinedPosts();
});

function fetchJoinedPosts() {
    fetch('../ajax/posts/api-joined-posts.php')
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            renderJoinedPosts(data.posts);
        } else {
            console.error("Errore nel caricamento dei post:", data.message);
        }
    })
    .catch(error => {
        console.error("Errore durante la fetch dei post:", error);
    });
}

function renderJoinedPosts(posts) {
    const container = document.getElementById('joined-posts-container');
    const emptyMessage = document.getElementById('joined-posts-empty');
    if (!container) return;

    container.innerHTML = '';

    if (!posts || posts.length === 0) {
        if (emptyMessage) emptyMessage.classList.remove('d-none');
        return;
    }

    if (emptyMessage) emptyMessage.classList.add('d-none');

    posts.forEach(post => {
        container.insertAdjacentHTML('beforeend', createJoinedPostCard(post));
    });

    // Inizializza tooltip Bootstrap
    if (typeof bootstrap !== 'undefined') {
        const tooltipTriggerList = document.querySelectorAll('[data-bs-toggle="tooltip"]');
        [...tooltipTriggerList].map(el => new bootstrap.Tooltip(el));
    }
}

function createJoinedPostCard(post) {
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

    // File allegati (come in my_posts.js)
    const filesHtml = post.files && post.files.length > 0 
        ? post.files.map(file => {
            const fileIcon = getFileIcon(file.tipo);
            const fileColor = getFileColor(file.tipo);
            return `
                <a href="../ajax/posts/download-file.php?id=${file.id}" 
                class="btn btn-sm btn-outline-secondary d-inline-flex align-items-center border-0 bg-white shadow-sm me-2 mb-2">
                    <em class="bi ${fileIcon} me-2 ${fileColor}"></em>${escapeHtml(file.nome)}
                </a>
            `;
        }).join('')
        : '<span class="text-muted small">Nessun file allegato</span>';

    return `
        <div class="card shadow-sm mb-4 border-0">
            <div class="card-body p-4">
                <!-- Titolo e Icona Tipo -->
                <div class="d-flex justify-content-between align-items-start mb-2">
                    <h2 class="h5 fw-bold mb-0 text-dark">${escapeHtml(post.titolo)}</h2>
                    <em class="bi ${iconClass} fs-4" style="color: var(--color-primary);" 
                        data-bs-toggle="tooltip" data-bs-placement="left" title="${escapeHtml(tooltipText)}" aria-hidden="true"></em>
                </div>

                <!-- Autore della pubblicazione -->
                <div class="d-flex align-items-center text-muted small mb-3">
                    <em class="bi bi-person-circle fs-5 me-2"></em>
                    <span>Pubblicato da <strong>${escapeHtml(post.creatore_nome)} ${escapeHtml(post.creatore_cognome)}</strong></span>
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
                            <strong>Luogo:</strong>&nbsp;${escapeHtml(post.luogo || 'Non specificato')}
                        </div>
                    </div>
                </div>

                <!-- Descrizione e File (AGGIUNTO) -->
                <div class="p-3 bg-light rounded-3 mb-4">
                    <h3 class="h6 fw-bold mb-2">Descrizione</h3>
                    <p class="small text-secondary mb-3">${escapeHtml(post.post_descrizione || 'Nessuna descrizione')}</p>
                    <h3 class="h6 fw-bold mb-2">Materiali Allegati</h3>
                    <div class="d-flex flex-wrap">${filesHtml}</div>
                </div>

                <!-- Bottoni e Data -->
                <div class="d-flex flex-wrap justify-content-between align-items-end mt-2">
                    <div class="row gx-2 mb-2 mb-sm-0 w-100 align-items-center">
                        <div class="col-6 col-sm-5 col-md-4 col-xl-3">
                            <a href="comments.php?post_id=${post.id}" class="btn btn-custom-primary w-100 fw-semibold">
                                <em class="bi bi-chat-dots me-1"></em>Commenta
                            </a>
                        </div>
                        <div class="col-6 col-sm-5 col-md-4 col-xl-3">
                            <button type="button" class="btn btn-outline-danger w-100 fw-semibold" onclick="esciDalPost(${post.id})">
                                <em class="bi bi-box-arrow-right me-1"></em>Esci
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

// === Helper functions (da my_posts.js) ===

function getFileIcon(mimeType) {
    if (!mimeType) return 'bi-file-earmark';
    if (mimeType.includes('pdf')) return 'bi-file-earmark-pdf';
    if (mimeType.includes('word') || mimeType.includes('doc')) return 'bi-file-earmark-word';
    if (mimeType.includes('excel') || mimeType.includes('xls')) return 'bi-file-earmark-excel';
    if (mimeType.includes('powerpoint') || mimeType.includes('ppt')) return 'bi-file-earmark-ppt';
    if (mimeType.includes('image')) return 'bi-file-earmark-image';
    if (mimeType.includes('text')) return 'bi-file-earmark-text';
    if (mimeType.includes('zip') || mimeType.includes('rar')) return 'bi-file-earmark-zip';
    return 'bi-file-earmark';
}

function getFileColor(mimeType) {
    if (!mimeType) return 'text-secondary';
    if (mimeType.includes('pdf')) return 'text-danger';
    if (mimeType.includes('word') || mimeType.includes('doc')) return 'text-primary';
    if (mimeType.includes('excel') || mimeType.includes('xls')) return 'text-success';
    if (mimeType.includes('powerpoint') || mimeType.includes('ppt')) return 'text-warning';
    if (mimeType.includes('image')) return 'text-info';
    return 'text-secondary';
}

function escapeHtml(text) {
    if (!text) return '';
    const div = document.createElement('div');
    div.textContent = text;
    return div.innerHTML;
}

function esciDalPost(postId) {
    if (!confirm("Sei sicuro di voler uscire da questo gruppo?")) {
        return;
    }

    fetch('../ajax/posts/api-leave-post.php?id=' + postId)
    .then((response) => { return response.json(); })
    .then((result) => {
        if (result.success) {
            alert(result.message);
            window.location.href = result.redirect || 'joined_posts.php';
        } else {
            alert(result.message);
        }
    })
    .catch(error => {
        console.error('Errore abbandono post:', error);
        alert('Errore di connessione. Riprova.');
    });
}
