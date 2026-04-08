document.addEventListener("DOMContentLoaded", () => {
    const postId = getPostIdFromUrl();
    if (!postId) {
        showError("ID post non valido nell'URL.");
        return;
    }
    
    loadPostData(postId);
    initFormSubmit(postId);
    initFileDeletion();
});

// === Utility: estrai ID dall'URL ===
function getPostIdFromUrl() {
    const params = new URLSearchParams(window.location.search);
    const id = params.get('id');
    return id && !isNaN(id) ? parseInt(id) : null;
}

// === Carica dati post e pre-compila form ===
function loadPostData(postId) {
    fetch(`../ajax/posts/api-post-info.php?id=${postId}`)
    .then(res => res.json())
    .then(data => {
        if (data.success) {
            fillForm(data.post);
            renderExistingFiles(data.files);
        } else {
            showError(data.message);
            setTimeout(() => window.location.href = 'my_posts.php', 2000);
        }
    })
    .catch(err => {
        console.error("Errore fetch post:", err);
        showError("Errore di connessione.");
    });
}

// === Pre-compila i campi del form ===
function fillForm(post) {
    // Campi modificabili
    if (document.getElementById('luogoIncontro')) {
        document.getElementById('luogoIncontro').value = post.luogo || '';
    }
    if (document.getElementById('dataInizio')) {
        document.getElementById('dataInizio').value = formatDateForInput(post.data_inizio);
    }
    if (document.getElementById('dataFine')) {
        document.getElementById('dataFine').value = post.data_fine ? formatDateForInput(post.data_fine) : '';
    }
    if (document.getElementById('richiedeApprovazione')) {
        document.getElementById('richiedeApprovazione').checked = parseInt(post.richiede_approvazione) === 1;
    }
    if (document.getElementById('descrizionePost')) {
        document.getElementById('descrizionePost').value = post.post_descrizione || '';
    }
    
    // Campi SOLO LETTURA (visualizzazione)
    const readOnlyFields = {
        'edit-post-titolo': post.titolo,
        'edit-post-materia': post.materia_nome,
        'edit-post-tipo': post.tipo === 'progettuale' ? 'Progetto di gruppo' : 'Sessione di studio',
        'edit-post-partecipanti': `${post.partecipanti_attuali || 0}/${post.max_partecipanti}`
    };
    
    Object.entries(readOnlyFields).forEach(([id, value]) => {
        const el = document.getElementById(id);
        if (el) el.textContent = value || 'N/A';
    });
}

// === Formatta data per input type="date" (YYYY-MM-DD) ===
function formatDateForInput(dateString) {
    if (!dateString) return '';
    const date = new Date(dateString);
    if (isNaN(date.getTime())) return '';
    return date.toISOString().split('T')[0];
}

// === Renderizza file esistenti con pulsante elimina ===
function renderExistingFiles(files) {
    const container = document.getElementById('existing-files-list');
    if (!container) return;
    
    container.innerHTML = '';
    
    if (!files || files.length === 0) {
        container.innerHTML = '<span class="text-muted small">Nessun file allegato.</span>';
        return;
    }
    
    files.forEach(file => {
        const fileIcon = getFileIcon(file.tipo);
        const fileColor = getFileColor(file.tipo);
        
        const item = document.createElement('div');
        item.className = 'list-group-item d-flex justify-content-between align-items-center';
        item.dataset.fileId = file.id;
        item.innerHTML = `
            <div class="d-flex align-items-center">
                <em class="bi ${fileIcon} me-2 ${fileColor} fs-5"></em>
                <span class="small text-dark">${escapeHtml(file.nome)}</span>
            </div>
            <button type="button" class="btn btn-link text-danger p-0 btn-delete-file" 
                    data-file-id="${file.id}" aria-label="Elimina ${escapeHtml(file.nome)}">
                <em class="bi bi-trash" aria-hidden="true"></em>
            </button>
        `;
        container.appendChild(item);
    });
}

// === Inizializza eliminazione file (delegazione eventi) ===
function initFileDeletion() {
    const container = document.getElementById('existing-files-list');
    if (!container) return;
    
    container.addEventListener('click', event => {
        const btn = event.target.closest('.btn-delete-file');
        if (!btn) return;
        
        const fileId = btn.dataset.fileId;
        const fileName = btn.closest('.list-group-item').querySelector('span').textContent;
        
        if (confirm(`Eliminare il file "${fileName}"?\n\nVerrà rimosso definitivamente.`)) {
            // Aggiungi l'ID alla lista di eliminazione (nascosta nel form)
            let deletedIds = document.getElementById('deleted-file-ids')?.value || '';
            deletedIds = deletedIds ? `${deletedIds},${fileId}` : fileId;
            
            if (!document.getElementById('deleted-file-ids')) {
                const hidden = document.createElement('input');
                hidden.type = 'hidden';
                hidden.id = 'deleted-file-ids';
                hidden.name = 'delete_files';
                document.getElementById('edit-post-form')?.appendChild(hidden);
            }
            document.getElementById('deleted-file-ids').value = deletedIds;
            
            // Rimuovi visivamente il file
            btn.closest('.list-group-item').remove();
            
            // Aggiorna messaggio se lista vuota
            if (container.children.length === 0) {
                container.innerHTML = '<span class="text-muted small">Nessun file allegato.</span>';
            }
        }
    });
}

// === Gestione submit form ===
function initFormSubmit(postId) {
    const form = document.getElementById('edit-post-form');
    if (!form) return;
    
    form.addEventListener('submit', event => {
        event.preventDefault();
        
        const btn = form.querySelector('button[type="submit"]');
        const oldText = btn.innerHTML;
        btn.disabled = true;
        btn.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span>Salvataggio...';
        
        const formData = new FormData(form);
        formData.append('id', postId);
        
        fetch(`../ajax/posts/api-modify-post.php?id=${postId}`, {
            method: 'POST',
            body: formData
        })
        .then(res => res.json())
        .then(result => {
            if (result.success) {
                alert(result.message);
                window.location.href = result.redirect || 'my_posts.php';
            } else {
                alert('Errore: ' + result.message);
                btn.disabled = false;
                btn.innerHTML = oldText;
            }
        })
        .catch(err => {
            console.error("Errore submit:", err);
            alert('Errore di connessione. Riprova.');
            btn.disabled = false;
            btn.innerHTML = oldText;
        });
    });
}

// === Helper functions ===
function showError(message) {
    const alertBox = document.createElement('div');
    alertBox.className = 'alert alert-danger alert-dismissible fade show mb-4';
    alertBox.role = 'alert';
    alertBox.innerHTML = `
        ${escapeHtml(message)}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    `;
    const form = document.getElementById('edit-post-form');
    form?.insertAdjacentElement('beforebegin', alertBox);
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

function escapeHtml(text) {
    if (!text) return '';
    const div = document.createElement('div');
    div.textContent = text;
    return div.innerHTML;
}