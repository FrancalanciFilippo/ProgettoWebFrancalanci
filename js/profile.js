document.addEventListener('DOMContentLoaded', async function () {
    await renderFields();
    addDeleteAccount();
    addModifyProfile();
});

const renderFields = async () => {
    try {
        const response = await fetch('../ajax/profile/api-get-profile.php');

        if (!response.ok) {
            throw new Error(`Errore HTTP: ${response.status}`);
        }

        const result = await response.json();

        if (!result.success) {
            console.warn('Profilo non caricato:', result.message);
            if (response.status === 401) {
                window.location.href = '../pages/login.php';
            }
            return;
        }

        const data = result.data;

        const nameField = document.getElementById('profile-name');
        if (nameField && data.nome) {
            nameField.value = data.nome;
        }

        const surnameField = document.getElementById('profile-surname');
        if (surnameField && data.cognome) {
            surnameField.value = data.cognome;
        }

        const emailField = document.getElementById('profile-email');
        if (emailField && data.email) {
            emailField.value = data.email;
        }

        const bioField = document.getElementById('profile-bio');
        if (bioField) {
            const bioValue = data.descrizione ?? data.bio ?? '';

            const wasDisabled = bioField.disabled;
            if (wasDisabled) bioField.disabled = false;

            bioField.value = bioValue;

            if (wasDisabled) {
                bioField.disabled = true;
                bioField.blur();
            }
        }

    } catch (error) {
        console.error('Errore nel caricamento del profilo:', error);
    }
};

const addModifyProfile = () => {
    const toggleBtn = document.getElementById('toggle-edit-btn');
    if (!toggleBtn) return;

    const editableFields = {
        nome: document.getElementById('profile-name'),
        cognome: document.getElementById('profile-surname'),
        email: document.getElementById('profile-email'),  // ← AGGIUNTO: email ora modificabile
        bio: document.getElementById('profile-bio')
    };

    // RIMOSSO: const emailField = document.getElementById('profile-email'); (ora è in editableFields)

    let isEditing = false;

    toggleBtn.addEventListener('click', async function () {
        if (!isEditing) {
            Object.values(editableFields).forEach(field => {  // ← ORA include anche email
                if (field) {
                    field.disabled = false;
                    field.style.backgroundColor = '#fff';
                }
            });

            // RIMOSSO: if (emailField) emailField.disabled = true;  ← email ora si abilita con gli altri

            toggleBtn.innerHTML = '<em class="bi bi-check-lg me-2" aria-hidden="true"></em>Salva';
            toggleBtn.classList.add('btn-success');
            toggleBtn.classList.remove('btn-custom-primary');
            editableFields.nome?.focus();

            isEditing = true;

        } else {
            // === SALVA LE MODIFICHE ===

            const originalBtnText = toggleBtn.innerHTML;
            toggleBtn.disabled = true;
            toggleBtn.innerHTML = '<span class="spinner-border spinner-border-sm me-2" role="status"></span>Salvataggio...';

            try {
                // Prepara payload (ORA INCLUDE EMAIL) ← MODIFICA QUI
                const payload = {
                    nome: editableFields.nome?.value,
                    cognome: editableFields.cognome?.value,
                    email: editableFields.email?.value,  // ← AGGIUNTO: email nel payload
                    descrizione: editableFields.bio?.value
                };

                const response = await fetch('../ajax/profile/api-modify-profile.php', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify(payload)
                });

                const result = await response.json();

                if (result.success) {
                    if (result.data) {
                        if (editableFields.nome) editableFields.nome.value = result.data.nome;
                        if (editableFields.cognome) editableFields.cognome.value = result.data.cognome;
                        if (editableFields.email) editableFields.email.value = result.data.email;  // ← AGGIUNTO: aggiorna email
                        if (editableFields.bio) editableFields.bio.value = result.data.descrizione;
                    }

                    Object.values(editableFields).forEach(field => {  // ← ORA disabilita anche email
                        if (field) {
                            field.disabled = true;
                            field.style.backgroundColor = '';
                        }
                    });

                    toggleBtn.innerHTML = originalBtnText;
                    toggleBtn.classList.remove('btn-success');
                    toggleBtn.classList.add('btn-custom-primary');
                    toggleBtn.innerHTML = '<em class="bi bi-pencil-square me-2" aria-hidden="true"></em>Modifica';
                    toggleBtn.disabled = false;
                    isEditing = false;

                    showToast(result.message, 'success');

                    // ← AGGIUNTO: se email cambiata, reload per sicurezza
                    if (result.email_changed) {
                        setTimeout(() => {
                            window.location.reload();
                        }, 1500);
                    }

                } else {
                    showToast('Errore: ' + result.message, 'danger');
                    
                    // ← AGGIUNTO: evidenzia campo email se errore duplicato
                    if (result.message === 'Email già registrata.' && editableFields.email) {
                        editableFields.email.classList.add('is-invalid');
                        setTimeout(() => editableFields.email.classList.remove('is-invalid'), 3000);
                    }
                    
                    toggleBtn.disabled = false;
                    toggleBtn.innerHTML = originalBtnText;
                }

            } catch (error) {
                console.error('Errore salvataggio:', error);
                showToast('Errore di connessione. Riprova più tardi.', 'danger');
                toggleBtn.disabled = false;
                toggleBtn.innerHTML = originalBtnText;
            }
        }
    });
};

// === Funzione toast (se non l'hai già) ===
function showToast(message, type = 'info') {
    if (typeof bootstrap !== 'undefined') {
        const toastEl = document.createElement('div');
        toastEl.className = `toast align-items-center text-bg-${type} border-0 position-fixed top-0 end-0 m-3`;
        toastEl.setAttribute('role', 'alert');
        toastEl.style.zIndex = '9999';
        toastEl.innerHTML = `
            <div class="d-flex">
                <div class="toast-body">${message}</div>
                <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast"></button>
            </div>
        `;
        document.body.appendChild(toastEl);
        const toast = new bootstrap.Toast(toastEl, { delay: 4000 });
        toast.show();
        toastEl.addEventListener('hidden.bs.toast', () => toastEl.remove());
    } else {
        alert(message);
    }
}

// === Funzione helper per toast Bootstrap (opzionale, altrimenti usa alert()) ===
function showToast(message, type = 'info') {
    // Se non usi Bootstrap, sostituisci con: alert(message);
    if (typeof bootstrap !== 'undefined') {
        const toastEl = document.createElement('div');
        toastEl.className = `toast align-items-center text-bg-${type} border-0 position-fixed top-0 end-0 m-3`;
        toastEl.setAttribute('role', 'alert');
        toastEl.style.zIndex = '9999';
        toastEl.innerHTML = `
            <div class="d-flex">
                <div class="toast-body">${message}</div>
                <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast"></button>
            </div>
        `;
        document.body.appendChild(toastEl);
        const toast = new bootstrap.Toast(toastEl, { delay: 3000 });
        toast.show();
        toastEl.addEventListener('hidden.bs.toast', () => toastEl.remove());
    } else {
        alert(message);
    }
}

const addDeleteAccount = () => {
    const confirmDeleteYesBtn = document.getElementById('confirmDeleteYesBtn');
    const confirmDeleteNoBtn = document.getElementById('confirmDeleteNoBtn');
    const confirmDeleteModal = document.getElementById('confirmDeleteModal');

    if (!confirmDeleteYesBtn || !confirmDeleteModal) {
        return;
    }

    confirmDeleteModal.addEventListener('show.bs.modal', function () {
        confirmDeleteNoBtn.focus();
    });

    confirmDeleteYesBtn.addEventListener('click', async function () {
        try {
            const response = await fetch('../ajax/profile/api-delete-account.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                }
            });

            const result = await response.json();

            if (result.success) {
                alert('Account eliminato con successo. Reindirizzamento in corso...');
                setTimeout(() => {
                    window.location.href = result.redirect;
                }, 1000);
            } else {
                alert('Errore: ' + result.message);
            }
        } catch (error) {
            console.error('Errore:', error);
            alert('Errore di connessione: ' + error.message);
        }
    });
}