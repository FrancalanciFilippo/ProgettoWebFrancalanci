document.addEventListener("DOMContentLoaded", () => {
    const urlParams = new URLSearchParams(window.location.search);
    const postId = urlParams.get('id');

    if (!postId) {
        console.error("ID post non trovato nell'URL");
        return;
    }

    fetch(`../ajax/posts/api-post-info.php?id=${postId}`)
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            renderPostInfo(data.post);
            renderFiles(data.files);
        } else {
            console.error("Errore nel caricamento del post:", data.message);
        }
    })
    .catch(error => {
        console.error("Errore durante la fetch del post:", error);
    });

    // Event listener per il bottone "Partecipa"
    document.addEventListener('click', event => {
        const btn = event.target.closest('[data-post-id]');
        if (btn) {
            event.preventDefault();
            const postId = parseInt(btn.dataset.postId);
            partecipaPost(postId);
        }
    });
});

function renderPostInfo(post) {
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

    // Titolo
    const titleEl = document.getElementById('post-title');
    if (titleEl) titleEl.textContent = post.titolo;

    // Icona tipo
    const iconEl = document.getElementById('post-type-icon');
    if (iconEl) {
        iconEl.className = post.tipo === 'progettuale' ? 'bi bi-diagram-3 fs-3' : 'bi bi-book fs-3';
        iconEl.style.color = 'var(--color-primary)';
    }

    // Autore e data
    const authorEl = document.getElementById('post-author');
    if (authorEl) {
        const authorName = `${post.creatore_nome} ${post.creatore_cognome}`;
        authorEl.innerHTML = `
            <em class="bi bi-person-circle fs-5 me-2"></em>
            <span>Pubblicato da <strong>${authorName}</strong></span>
            <span class="ms-3"><em class="bi bi-clock me-1"></em>${formatDateTime(post.data_creazione)}</span>
        `;
    }

    // Materia
    const materiaEl = document.getElementById('post-materia');
    if (materiaEl) materiaEl.textContent = post.materia_nome;

    // Partecipanti
    const partecipantiEl = document.getElementById('post-partecipanti');
    if (partecipantiEl) partecipantiEl.textContent = `${post.partecipanti_attuali || 0} / ${post.max_partecipanti}`;

    // Periodo
    const periodoEl = document.getElementById('post-periodo');
    if (periodoEl) {
        const dataInizio = post.data_inizio ? new Date(post.data_inizio).toLocaleDateString('it-IT') : 'N/A';
        const dataFine = post.data_fine ? new Date(post.data_fine).toLocaleDateString('it-IT') : 'N/A';
        periodoEl.textContent = `${dataInizio} – ${dataFine}`;
    }

    // Luogo
    const luogoEl = document.getElementById('post-luogo');
    if (luogoEl) luogoEl.textContent = post.luogo || 'Non specificato';

    // Approvazione
    const approvazioneEl = document.getElementById('post-approvazione');
    if (approvazioneEl) {
        const richiedeApprovazione = parseInt(post.richiede_approvazione, 10) === 1;
        approvazioneEl.textContent = richiedeApprovazione ? 'Richiesta' : 'Non richiesta (partecipazione diretta)';
    }

    // Tipo
    const tipoEl = document.getElementById('post-tipo');
    if (tipoEl) tipoEl.textContent = post.tipo === 'progettuale' ? 'Progetto di gruppo' : 'Sessione di studio';

    // Descrizione
    const descrizioneEl = document.getElementById('post-descrizione');
    if (descrizioneEl) descrizioneEl.innerHTML = post.post_descrizione;

    // Bottone azione
    const buttonEl = document.getElementById('post-action-btn');
    if (buttonEl) {
        const richiedeApprovazione = parseInt(post.richiede_approvazione, 10) === 1;
        buttonEl.setAttribute('data-post-id', post.id);
        buttonEl.innerHTML = `
            <em class="bi bi-person-plus me-2" aria-hidden="true"></em>${richiedeApprovazione ? 'Invia richiesta' : 'Partecipa'}
        `;
    }
}


function renderFiles(files) {
    const container = document.getElementById('post-files');
    if (!container) return;

    container.innerHTML = '';

    if (files.length === 0) {
        container.innerHTML = '<p class="text-muted">Nessun materiale allegato.</p>';
        return;
    }

    files.forEach(file => {
        const fileSize = formatFileSize(file.dimensione_byte);
        const fileIcon = getFileIcon(file.tipo);
        const fileColor = getFileColor(file.tipo);

        const fileElement = document.createElement('a');
        fileElement.href = `../ajax/posts/download-file.php?id=${file.id}`;
        fileElement.className = 'd-flex align-items-center p-3 bg-light rounded-3 text-decoration-none text-dark border';
        fileElement.innerHTML = `
            <em class="bi ${fileIcon} me-3 ${fileColor} fs-4"></em>
            <div>
                <div class="fw-semibold small">${file.nome}</div>
                <div class="text-muted" style="font-size:0.75rem;">${file.tipo || 'File'} &middot; ${fileSize}</div>
            </div>
            <em class="bi bi-download ms-auto text-muted"></em>
        `;

        container.appendChild(fileElement);
    });
}

function formatFileSize(bytes) {
    if (bytes === 0) return '0 Bytes';
    const k = 1024;
    const sizes = ['Bytes', 'KB', 'MB', 'GB'];
    const i = Math.floor(Math.log(bytes) / Math.log(k));
    return parseFloat((bytes / Math.pow(k, i)).toFixed(1)) + ' ' + sizes[i];
}

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
            window.location.href = 'joined_posts.php';
        } else {
            alert(data.message || 'Errore durante la partecipazione.');
        }
    })
    .catch(error => {
        console.error('Errore durante la partecipazione:', error);
        alert('Errore di connessione. Riprova.');
    });
}