document.addEventListener("DOMContentLoaded", function () {
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
            console.error("Errore nel caricamento del post:", data.error);
        }
    })
    .catch(error => {
        console.error("Errore durante la fetch del post:", error);
    });
});

function renderPostInfo(post) {
    // Titolo
    const titleElement = document.querySelector('.card-body h1');
    if (titleElement) {
        titleElement.textContent = post.titolo;
    }

    // Icona tipo
    const iconElement = document.querySelector('.card-body .bi');
    if (iconElement) {
        iconElement.className = post.tipo === 'progettuale' ? 'bi bi-diagram-3 fs-3' : 'bi bi-book fs-3';
        iconElement.style.color = 'var(--color-primary)';
    }

    // Autore e data
    const authorElement = document.querySelector('.card-body .text-muted');
    if (authorElement) {
        const authorName = `${post.creatore_nome} ${post.creatore_cognome}`;
        const date = new Date(post.data_creazione).toLocaleDateString('it-IT');
        authorElement.innerHTML = `
            <em class="bi bi-person-circle fs-5 me-2"></em>
            <span>Pubblicato da <strong>${authorName}</strong></span>
            <span class="ms-3"><em class="bi bi-clock me-1"></em>${date}</span>
        `;
    }

    // Materia
    const materiaElement = document.querySelector('.card-body .bi-tag').nextElementSibling;
    if (materiaElement) {
        materiaElement.querySelector('.fw-semibold').textContent = post.materia_nome;
    }

    // Partecipanti
    const partecipantiElement = document.querySelector('.card-body .bi-people').nextElementSibling;
    if (partecipantiElement) {
        partecipantiElement.querySelector('.fw-semibold').textContent = `${post.partecipanti_attuali || 0} / ${post.max_partecipanti}`;
    }

    // Periodo
    const periodoElement = document.querySelector('.card-body .bi-calendar-event').nextElementSibling;
    if (periodoElement) {
        const dataInizio = post.data_inizio ? new Date(post.data_inizio).toLocaleDateString('it-IT') : 'N/A';
        const dataFine = post.data_fine ? new Date(post.data_fine).toLocaleDateString('it-IT') : 'N/A';
        periodoElement.querySelector('.fw-semibold').textContent = `${dataInizio} – ${dataFine}`;
    }

    // Luogo
    const luogoElement = document.querySelector('.card-body .bi-geo-alt').nextElementSibling;
    if (luogoElement) {
        luogoElement.querySelector('.fw-semibold').textContent = post.luogo || 'Non specificato';
    }

    // Approvazione
    const approvazioneElement = document.querySelector('.card-body .bi-shield-check').nextElementSibling;
    if (approvazioneElement) {
        const richiedeApprovazione = parseInt(post.richiede_approvazione, 10) === 1;
        approvazioneElement.querySelector('.fw-semibold').textContent = richiedeApprovazione ? 'Richiesta' : 'Non richiesta (partecipazione diretta)';
    }

    // Tipo
    const tipoElement = document.querySelector('.card-body .bi-grid').nextElementSibling;
    if (tipoElement) {
        tipoElement.querySelector('.fw-semibold').textContent = post.tipo === 'progettuale' ? 'Progetto di gruppo' : 'Sessione di studio';
    }

    // Descrizione
    const descrizioneElement = document.querySelector('.card-body .bg-light p');
    if (descrizioneElement) {
        descrizioneElement.innerHTML = post.post_descrizione;
    }

    // Bottone partecipazione
    const buttonElement = document.querySelector('.card-body .btn');
    if (buttonElement) {
        const richiedeApprovazione = parseInt(post.richiede_approvazione, 10) === 1;
        buttonElement.innerHTML = `
            <em class="bi bi-person-plus me-2" aria-hidden="true"></em>${richiedeApprovazione ? 'Invia richiesta' : 'Partecipa'}
        `;
    }
}

function renderFiles(files) {
    const container = document.querySelector('.card-body .d-flex.flex-column.gap-2');
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
        fileElement.href = `../ajax/posts/download-file.php?id=${file.id}`; // TODO: creare endpoint download
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