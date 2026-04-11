document.addEventListener("DOMContentLoaded", () => {
    const userId = getUserIdFromUrl();
    if (!userId) {
        showError("ID utente non valido nell'URL.");
        return;
    }
    
    loadUserData(userId);
    initFormSubmit(userId);
});

function getUserIdFromUrl() {
    const params = new URLSearchParams(window.location.search);
    const id = params.get('id');
    return id && !isNaN(id) ? parseInt(id) : null;
}

function loadUserData(userId) {
    fetch(`../ajax/admin/api-admin-get-user.php?id=${userId}`)
    .then(res => res.json())
    .then(data => {
        if (data.success && data.user) {
            fillForm(data.user, userId);
        } else {
            showError(data.message || "Utente non trovato");
            setTimeout(() => window.location.href = 'admin_users.php', 2000);
        }
    })
    .catch(err => {
        console.error("Errore fetch utente:", err);
        showError("Errore di connessione.");
    });
}

function fillForm(user, userId) {
    document.getElementById('edit-user-email').textContent = user.email || 'Senza email';
    
    if (document.getElementById('name')) {
        document.getElementById('name').value = user.nome || '';
    }
    if (document.getElementById('surname')) {
        document.getElementById('surname').value = user.cognome || '';
    }
    if (document.getElementById('email')) {
        document.getElementById('email').value = user.email || '';
    }
    if (document.getElementById('bio')) {
        document.getElementById('bio').value = user.descrizione || '';
    }
    
    // Aggiungi input nascosto per l'ID dell'utente
    const form = document.getElementById('admin-edit-user-form');
    const userIdInput = document.createElement('input');
    userIdInput.type = 'hidden';
    userIdInput.name = 'user_id';
    userIdInput.value = userId;
    form.appendChild(userIdInput);
}

function initFormSubmit(userId) {
    const form = document.getElementById('admin-edit-user-form');
    if (!form) return;
    
    form.addEventListener('submit', event => {
        event.preventDefault();
        
        const btn = document.getElementById('save-user-btn');
        const oldText = btn.innerHTML;
        btn.disabled = true;
        btn.innerHTML = 'Salvataggio...';
        
        const formData = new FormData(form);
        formData.append('user_id', userId);
        
        fetch(`../ajax/admin/api-admin-update-user.php`, {
            method: 'POST',
            body: formData
        })
        .then(res => res.json())
        .then(result => {
            if (result.success) {
                alert(result.message || 'Utente modificato con successo');
                window.location.href = 'admin_users.php';
            } else {
                alert('Errore: ' + (result.message || 'Fallimento sconosciuto'));
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
    alert('Errore: ' + message);
}
