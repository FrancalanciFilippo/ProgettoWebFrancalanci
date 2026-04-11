document.addEventListener('DOMContentLoaded', () => {
    // Carica utenti se siamo sulla pagina admin_users
    const usersTbody = document.getElementById('users-tbody');
    if (usersTbody) {
        loadUsers();
    }

    // Carica post se siamo sulla pagina admin_posts
    const postsTbody = document.getElementById('posts-tbody');
    if (postsTbody) {
        loadPosts();
    }

    // Inizializzazione form modifica utente (se presente)
    const editUserForm = document.getElementById('admin-edit-user-form');
    if (editUserForm) {
        editUserForm.addEventListener('submit', handleEditUserSubmit);
    }

    // Inizializzazione avatar (colori e iniziali dinamiche)
    initializeAvatars();
});

/**
 * Carica e renderizza utenti
 */
function loadUsers() {
    fetch('../ajax/admin/api-get-users.php')
        .then(res => res.json())
        .then(data => {
            if (data.success && data.users) {
                renderUsers(data.users);
            } else {
                renderUsersEmpty();
            }
        })
        .catch(err => {
            console.error(err);
            renderUsersError();
        });
}

/**
 * Renderizza utenti in tabella
 */
function renderUsers(users) {
    const tbody = document.getElementById('users-tbody');
    if (!tbody) return;

    if (users.length === 0) {
        tbody.innerHTML = '<tr><td colspan="3" class="text-center py-5 text-muted">Nessun utente registrato al momento.</td></tr>';
        return;
    }

    tbody.innerHTML = users.map(user => `
        <tr id="user-row-${user.id}">
            <td class="ps-4 py-3">
                <div class="d-flex align-items-center">
                    <div class="admin-user-avatar text-white me-3 d-flex align-items-center justify-content-center fw-bold rounded-circle shadow-sm flex-shrink-0" 
                         style="width: 40px; height: 40px;" 
                         data-user="${user.nome} ${user.cognome}"></div>
                    <div>
                        <div class="fw-bold text-dark">${user.nome} ${user.cognome}</div>
                        <div class="text-muted small">ID: #${user.id}</div>
                    </div>
                </div>
            </td>
            <td class="py-3">
                <span class="text-secondary">${user.email}</span>
            </td>
            <td class="pe-4 py-3 text-end">
                <div class="d-flex justify-content-end gap-2 text-nowrap">
                    <a href="admin_edit_user.php?id=${user.id}" class="btn btn-warning btn-sm fw-semibold text-dark">
                        <em class="bi bi-pencil me-1"></em>Modifica
                    </a>
                    <button type="button" class="btn btn-danger btn-sm fw-semibold" onclick="deleteUser(${user.id}, '${user.email}')">
                        <em class="bi bi-trash me-1"></em>Elimina
                    </button>
                </div>
            </td>
        </tr>
    `).join('');

    initializeAvatars();
}

/**
 * Renderizza messaggio vuoto utenti
 */
function renderUsersEmpty() {
    const tbody = document.getElementById('users-tbody');
    if (tbody) {
        tbody.innerHTML = '<tr><td colspan="3" class="text-center py-5 text-muted">Nessun utente registrato al momento.</td></tr>';
    }
}

/**
 * Renderizza errore caricamento utenti
 */
function renderUsersError() {
    const tbody = document.getElementById('users-tbody');
    if (tbody) {
        tbody.innerHTML = '<tr><td colspan="3" class="text-center py-5 text-danger">Errore nel caricamento degli utenti.</td></tr>';
    }
}

/**
 * Carica e renderizza post
 */
function loadPosts() {
    fetch('../ajax/admin/api-get-posts.php')
        .then(res => res.json())
        .then(data => {
            if (data.success && data.posts) {
                renderPosts(data.posts);
            } else {
                renderPostsEmpty();
            }
        })
        .catch(err => {
            console.error(err);
            renderPostsError();
        });
}

/**
 * Renderizza post in tabella
 */
function renderPosts(posts) {
    const tbody = document.getElementById('posts-tbody');
    if (!tbody) return;

    if (posts.length === 0) {
        tbody.innerHTML = '<tr><td colspan="3" class="text-center py-5 text-muted">Nessun post pubblicato al momento.</td></tr>';
        return;
    }

    tbody.innerHTML = posts.map(post => `
        <tr id="post-row-${post.id}">
            <td class="ps-4 py-3">
                <div class="fw-bold text-dark">${post.titolo}</div>
                <div class="text-muted small">ID: #${post.id}</div>
            </td>
            <td class="py-3">
                <div class="d-flex align-items-center">
                    <em class="bi bi-person-circle me-2 text-secondary"></em>
                    <div>
                        <div class="small fw-semibold">${post.creatore_nome} ${post.creatore_cognome}</div>
                        <div class="text-muted extra-small" style="font-size: 0.75rem;">${post.creatore_email}</div>
                    </div>
                </div>
            </td>
            <td class="pe-4 py-3 text-end">
                <div class="d-flex justify-content-end gap-2 text-nowrap">
                    <a href="admin_edit_post.php?id=${post.id}" class="btn btn-warning btn-sm fw-semibold text-dark">
                        <em class="bi bi-pencil me-1"></em>Modifica
                    </a>
                    <button type="button" class="btn btn-danger btn-sm fw-semibold" onclick="deletePost(${post.id}, '${post.titolo.replace(/'/g, "\\'")}')">
                        <em class="bi bi-trash me-1"></em>Elimina
                    </button>
                </div>
            </td>
        </tr>
    `).join('');
}

/**
 * Renderizza messaggio vuoto post
 */
function renderPostsEmpty() {
    const tbody = document.getElementById('posts-tbody');
    if (tbody) {
        tbody.innerHTML = '<tr><td colspan="3" class="text-center py-5 text-muted">Nessun post pubblicato al momento.</td></tr>';
    }
}

/**
 * Renderizza errore caricamento post
 */
function renderPostsError() {
    const tbody = document.getElementById('posts-tbody');
    if (tbody) {
        tbody.innerHTML = '<tr><td colspan="3" class="text-center py-5 text-danger">Errore nel caricamento dei post.</td></tr>';
    }
}

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
    const oldText = btn ? btn.innerHTML : '';
    
    if (btn) {
        btn.disabled = true;
        btn.innerHTML = 'Salvataggio...';
    }
    
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
            if (btn) {
                btn.disabled = false;
                btn.innerHTML = oldText;
            }
        }
    })
    .catch(err => {
        console.error(err);
        alert('Errore di connessione.');
        if (btn) {
            btn.disabled = false;
            btn.innerHTML = oldText;
        }
    });
}
