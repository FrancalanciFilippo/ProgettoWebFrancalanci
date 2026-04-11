document.addEventListener('DOMContentLoaded', () => {
    const usersTbody = document.getElementById('users-tbody');
    if (usersTbody) {
        loadUsers();
    }

    const editUserForm = document.getElementById('admin-edit-user-form');
    if (editUserForm) {
        editUserForm.addEventListener('submit', handleEditUserSubmit);
    }

    initializeAvatars();
});

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

function renderUsersEmpty() {
    const tbody = document.getElementById('users-tbody');
    if (tbody) {
        tbody.innerHTML = '<tr><td colspan="3" class="text-center py-5 text-muted">Nessun utente registrato al momento.</td></tr>';
    }
}

function renderUsersError() {
    const tbody = document.getElementById('users-tbody');
    if (tbody) {
        tbody.innerHTML = '<tr><td colspan="3" class="text-center py-5 text-danger">Errore nel caricamento degli utenti.</td></tr>';
    }
}

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

function getInitials(fullName) {
    return fullName.split(' ').filter(Boolean).slice(0, 2).map(word => word[0].toUpperCase()).join('');
}

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

function deleteUser(userId, email) {
    if (confirm(`Sei sicuro di voler eliminare DEFINITIVAMENTE l'utente ${email}?\n\nTutti i suoi post e commenti verranno rimossi.`)) {
        const formData = new FormData();
        formData.append('id', userId);

        fetch('../ajax/admin/api-admin-delete-user.php', {
            method: 'POST',
            body: formData
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
