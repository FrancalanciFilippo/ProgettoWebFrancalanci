document.addEventListener('DOMContentLoaded', () => {
    // Inizializzazione form modifica utente (se presente)
    const editUserForm = document.getElementById('admin-edit-user-form');
    if (editUserForm) {
        editUserForm.addEventListener('submit', handleEditUserSubmit);
    }

    // Inizializzazione avatar (colori e iniziali dinamiche)
    initializeAvatars();
});

/**
 * Helper per generare un colore dall'hash del nome (coerente con comments.js)
 */
function hashColor(str) {
    const palette = [
        '#2e7d32', '#1565c0', '#6a1b9a', '#c62828', 
        '#f57f17', '#00695c', '#283593', '#4e342e'
    ];
    let hash = 0;
    for (let i = 0; i < str.length; i++) {
        hash = str.charCodeAt(i) + ((hash << 5) - hash);
    }
    return palette[Math.abs(hash) % palette.length];
}

/**
 * Helper per ottenere le iniziali
 */
function getInitials(fullName) {
    return fullName.split(' ').filter(Boolean).slice(0, 2).map(word => word[0].toUpperCase()).join('');
}

/**
 * Applica stili e iniziali agli avatar amministrativi
 */
function initializeAvatars() {
    const avatars = document.querySelectorAll('.admin-user-avatar');
    avatars.forEach(avatar => {
        const userName = avatar.getAttribute('data-user');
        if (userName) {
            avatar.style.backgroundColor = hashColor(userName);
            if (avatar.textContent.trim() === "") {
                avatar.textContent = getInitials(userName);
            }
        }
    });
}

/**
 * Elimina un utente via AJAX
 */
function deleteUser(userId, email) {
    if (confirm(`Sei sicuro di voler eliminare DEFINITIVAMENTE l'utente ${email}?\n\nTutti i suoi post e commenti verranno rimossi.`)) {
        fetch('../ajax/admin/api-admin-delete.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ type: 'user', id: userId })
        })
        .then(res => res.json())
        .then(data => {
            if (data.success) {
                const row = document.getElementById(`user-row-${userId}`);
                if (row) {
                    row.classList.add('fade-out');
                    setTimeout(() => row.remove(), 400);
                }
                alert(data.message);
            } else {
                alert('Errore: ' + data.message);
            }
        })
        .catch(err => {
            console.error(err);
            alert('Errore di connessione.');
        });
    }
}

/**
 * Elimina un post via AJAX
 */
function deletePost(postId, title) {
    if (confirm(`Vuoi procedere all'eliminazione del post: "${title}"?`)) {
        fetch('../ajax/admin/api-admin-delete.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ type: 'post', id: postId })
        })
        .then(res => res.json())
        .then(data => {
            if (data.success) {
                const row = document.getElementById(`post-row-${postId}`);
                if (row) {
                    row.classList.add('fade-out');
                    setTimeout(() => row.remove(), 400);
                }
                alert(data.message);
            } else {
                alert('Errore: ' + data.message);
            }
        })
        .catch(err => {
            console.error(err);
            alert('Errore di connessione.');
        });
    }
}

/**
 * Salvataggio modifiche profilo utente (lato admin)
 */
function handleEditUserSubmit(e) {
    e.preventDefault();
    const form = e.target;
    const btn = document.getElementById('save-user-btn');
    
    if (btn) btn.disabled = true;
    
    const formData = new FormData(form);
    
    fetch('../ajax/admin/api-admin-update-user.php', {
        method: 'POST',
        body: formData
    })
    .then(res => res.json())
    .then(data => {
        if (data.success) {
            alert('Dati utente aggiornati con successo!');
            window.location.href = 'admin_users.php';
        } else {
            alert('Errore: ' + data.message);
            if (btn) btn.disabled = false;
        }
    })
    .catch(err => {
        console.error(err);
        alert('Errore di connessione.');
        if (btn) btn.disabled = false;
    });
}

// Stile per l'animazione di uscita
const style = document.createElement('style');
style.textContent = `
    .fade-out {
        opacity: 0;
        transform: translateX(20px);
        transition: all 0.4s ease;
    }
`;
document.head.appendChild(style);
