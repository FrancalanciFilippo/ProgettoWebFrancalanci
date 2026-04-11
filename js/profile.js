document.addEventListener('DOMContentLoaded', () => {
    renderFields();
    addDeleteAccount();
    addModifyProfile();
});

function renderFields() {
    fetch('../ajax/profile/api-get-profile.php')
    .then(response => response.json())
    .then(result => {
        if (!result.success) {
            console.warn('Profilo non caricato:', result.message);
            return;
        }

        var data = result.data;

        var nameField = document.getElementById('profile-name');
        if (nameField && data.nome) nameField.value = data.nome;

        var surnameField = document.getElementById('profile-surname');
        if (surnameField && data.cognome) surnameField.value = data.cognome;

        var emailField = document.getElementById('profile-email');
        if (emailField && data.email) emailField.value = data.email;

        var bioField = document.getElementById('profile-bio');
        if (bioField) {
            var bioValue = data.descrizione || data.bio || '';
            var wasDisabled = bioField.disabled;
            if (wasDisabled) bioField.disabled = false;
            bioField.value = bioValue;
            if (wasDisabled) {
                bioField.disabled = true;
                bioField.blur();
            }
        }
    })
    .catch(error => {
        console.error('Errore nel caricamento del profilo:', error);
    });
}

function addModifyProfile() {
    var toggleBtn = document.getElementById('toggle-edit-btn');
    if (!toggleBtn) return;

    var editableFields = {
        nome: document.getElementById('profile-name'),
        cognome: document.getElementById('profile-surname'),
        email: document.getElementById('profile-email'),
        bio: document.getElementById('profile-bio')
    };

    var isEditing = false;

    toggleBtn.addEventListener('click', () => {
        if (!isEditing) {

            Object.values(editableFields).forEach(field => {
                if (field) {
                    field.disabled = false;
                    field.style.backgroundColor = '#fff';
                }
            });

            toggleBtn.innerHTML = '<em class="bi bi-check-lg me-2" aria-hidden="true"></em>Salva';
            toggleBtn.classList.add('btn-success');
            toggleBtn.classList.remove('btn-custom-primary');
            if (editableFields.nome) editableFields.nome.focus();

            isEditing = true;

        } else {

            var originalBtnText = toggleBtn.innerHTML;
            toggleBtn.disabled = true;
            toggleBtn.innerHTML = 'Salvataggio...';

            const form = document.getElementById('profile-form');
            const formData = new FormData(form);
            formData.set('nome', editableFields.nome.value);
            formData.set('cognome', editableFields.cognome.value);
            formData.set('email', editableFields.email.value);
            formData.set('descrizione', editableFields.bio.value);

            fetch('../ajax/profile/api-modify-profile.php', {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(result => {
                if (result.success) {
                    if (result.data) {
                        if (editableFields.nome) editableFields.nome.value = result.data.nome;
                        if (editableFields.cognome) editableFields.cognome.value = result.data.cognome;
                        if (editableFields.email) editableFields.email.value = result.data.email;
                        if (editableFields.bio) editableFields.bio.value = result.data.descrizione;
                    }

                    Object.values(editableFields).forEach(field => {
                        if (field) {
                            field.disabled = true;
                            field.style.backgroundColor = '';
                        }
                    });

                    toggleBtn.classList.remove('btn-success');
                    toggleBtn.classList.add('btn-custom-primary');
                    toggleBtn.innerHTML = '<em class="bi bi-pencil-square me-2" aria-hidden="true"></em>Modifica';
                    toggleBtn.disabled = false;
                    isEditing = false;

                    alert(result.message);

                    if (result.email_changed) {
                        setTimeout(() => {
                            window.location.reload();
                        }, 1000);
                    }

                } else {
                    alert('Errore: ' + result.message);

                    if (result.message === 'Email gia registrata.' && editableFields.email) {
                        editableFields.email.classList.add('is-invalid');
                        setTimeout(() => { editableFields.email.classList.remove('is-invalid'); }, 3000);
                    }

                    toggleBtn.disabled = false;
                    toggleBtn.innerHTML = originalBtnText;
                }
            })
            .catch(error => {
                console.error('Errore salvataggio:', error);
                alert('Errore di connessione. Riprova piu tardi.');
                toggleBtn.disabled = false;
                toggleBtn.innerHTML = originalBtnText;
            });
        }
    });
}

function addDeleteAccount() {
    var eliminaBtn = document.getElementById('profile-elimina');
    if (!eliminaBtn) return;

    eliminaBtn.addEventListener('click', () => {
        if (!confirm('Sei sicuro di voler eliminare il tuo account?\nQuesta azione è irreversibile. Tutti i tuoi dati verranno eliminati dal sistema.')) {
            return;
        }

        const formData = new FormData();

        fetch('../ajax/profile/api-delete-account.php', {
            method: 'POST',
            body: formData
        })
        .then(response => response.json())
        .then(result => {
            if (result.success) {
                alert('Account eliminato con successo. Reindirizzamento in corso...');
                setTimeout(() => {
                    window.location.href = result.redirect;
                }, 1000);
            } else {
                alert('Errore: ' + result.message);
            }
        })
        .catch(error => {
            console.error('Errore:', error);
            alert('Errore di connessione: ' + error.message);
        });
    });
}