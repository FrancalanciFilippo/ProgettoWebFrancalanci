document.addEventListener("DOMContentLoaded", () => {
    const postId = getPostIdFromUrl();
    if (!postId) {
        showError("ID post non valido nell'URL.");
        return;
    }
    
    loadPostData(postId);
    loadParticipants(postId);
    initFormSubmit(postId);
});
window.kickedParticipantIds = [];

function getPostIdFromUrl() {
    const params = new URLSearchParams(window.location.search);
    const id = params.get('id');
    return id && !isNaN(id) ? parseInt(id) : null;
}

function loadPostData(postId) {
    fetch(`../ajax/posts/api-post-info.php?id=${postId}`)
    .then(res => res.json())
    .then(data => {
        if (data.success) {
            fillForm(data.post);
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

function fillForm(post) {
    if (document.getElementById('luogoIncontro')) {
        document.getElementById('luogoIncontro').value = post.luogo || '';
    }
    if (document.getElementById('dataInizio')) {
        document.getElementById('dataInizio').value = formatDateForInput(post.data_inizio);
    }
    if (document.getElementById('dataFine')) {
        document.getElementById('dataFine').value = post.data_fine ? formatDateForInput(post.data_fine) : '';
    }
    if (document.getElementById('descrizionePost')) {
        document.getElementById('descrizionePost').value = post.descrizione || '';
    }
}

function formatDateForInput(dateString) {
    if (!dateString) return '';
    const date = new Date(dateString);
    if (isNaN(date.getTime())) return '';
    return date.toISOString().split('T')[0];
}

function initFormSubmit(postId) {
    const form = document.getElementById('edit-post-form');
    if (!form) return;
    
    form.addEventListener('submit', event => {
        event.preventDefault();
        
        const btn = document.getElementById('edit-post-submit');
        const oldText = btn.innerHTML;
        btn.disabled = true;
        btn.innerHTML = 'Salvataggio...';
        
        const formData = new FormData(form);
        formData.append('id', postId);
        

        if (window.kickedParticipantIds && window.kickedParticipantIds.length > 0) {
            formData.append('delete_participants', window.kickedParticipantIds.join(','));
        }
        
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

function loadParticipants(postId) {
    const container = document.getElementById('partecipanti');
    if (!container) return;

    fetch(`../ajax/posts/api-get-participants.php?id=${postId}`)
        .then(res => res.json())
        .then(data => {
            if (data.success) {
                renderParticipantsList(data.participants, postId);
            } else {
                container.innerHTML = `<div class="list-group-item text-danger small py-3">${data.message}</div>`;
            }
        })
        .catch(err => {
            console.error("Errore caricamento partecipanti:", err);
            container.innerHTML = '<div class="list-group-item text-danger small py-3">Errore di connessione.</div>';
        });
}

function renderParticipantsList(participants, postId) {
    const container = document.getElementById('partecipanti');
    if (!container) return;

    container.innerHTML = '';

    if (!participants || participants.length === 0) {
        container.innerHTML = '<div class="list-group-item text-muted small py-3">Nessun partecipante iscritto al momento.</div>';
        return;
    }

    participants.forEach(user => {
        const item = document.createElement('div');
        item.className = 'list-group-item d-flex justify-content-between align-items-center';
        
        const fullName = `${user.nome} ${user.cognome}`;
        const initials = (user.nome[0] + user.cognome[0]).toUpperCase();
        const bgColor = hashColor(fullName);
        
        item.innerHTML = `
            <div class="d-flex align-items-center">
                <div class="rounded-circle text-white d-flex align-items-center justify-content-center me-2" 
                     style="width: 32px; height: 32px; font-weight: bold; font-size: 0.8rem; background-color: ${bgColor};">
                    ${initials}
                </div>
                <span class="small text-dark">${fullName}</span>
            </div>
            <button type="button" class="btn btn-link text-danger p-0 kick-user-btn" 
                    onclick="confirmKickParticipant(this, ${postId}, ${user.id}, '${fullName.replace(/'/g, "\\'")}')"
                    title="Rimuovi utente">
                <em class="bi bi-x-lg"></em>
            </button>
        `;
        container.appendChild(item);
    });
}

function confirmKickParticipant(btn, postId, userId, fullName) {
    if (confirm(`Sei sicuro di voler rimuovere ${fullName} da questo post?`)) {
        if (!window.kickedParticipantIds.includes(userId)) {
            window.kickedParticipantIds.push(userId);
        }

        const item = btn.closest('.list-group-item');
        if (item) item.remove();
        
        const countEl = document.getElementById('edit-post-partecipanti');
        if (countEl) {
            const parts = countEl.textContent.trim().split('/');
            if (parts.length === 2) {
                const currentCount = parseInt(parts[0]);
                const maxCount = parseInt(parts[1]);
                countEl.textContent = `${Math.max(0, currentCount - 1)}/${maxCount}`;
            }
        }
        
        const container = document.getElementById('partecipanti');
        if (container && container.children.length === 0) {
            container.innerHTML = '<div class="list-group-item text-muted small py-3">Nessun partecipante iscritto al momento.</div>';
        }
    }
}

function hashColor(str) {
    const palette = ['#2e7d32', '#1565c0', '#6a1b9a', '#c62828', '#f57f17', '#00695c', '#283593', '#4e342e'];
    let hash = 0;
    for (let i = 0; i < str.length; i++) {
        hash = str.charCodeAt(i) + ((hash << 5) - hash);
    }
    return palette[Math.abs(hash) % palette.length];
}

function escapeHtml(text) {
    if (!text) return '';
    const div = document.createElement('div');
    div.textContent = text;
    return div.innerHTML;
}