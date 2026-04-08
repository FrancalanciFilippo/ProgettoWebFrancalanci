document.addEventListener('DOMContentLoaded', () => {
    var signupForm = document.getElementById('signup-form');
    var signupSubmit = document.getElementById('signup-submit');

    if (!signupForm) return;

    signupForm.addEventListener('submit', event => {
        event.preventDefault();

        var nome = document.getElementById('signup-name').value.trim();
        var cognome = document.getElementById('signup-surname').value.trim();
        var email = document.getElementById('signup-email').value.trim();
        var password = document.getElementById('signup-password').value.trim();
        var password_confirm = document.getElementById('signup-password-confirm').value.trim();
        var bio = document.getElementById('signup-bio').value.trim();

        if (!nome || !cognome || !email || !password || !password_confirm) {
            showMessage('Tutti i campi obbligatori devono essere compilati.', 'error');
            return;
        }

        var data = { nome: nome, cognome: cognome, email: email, password: password, password_confirm: password_confirm, bio: bio };

        signupSubmit.disabled = true;
        var originalText = signupSubmit.innerText;
        signupSubmit.innerText = 'Registrazione in corso...';

        fetch('../ajax/login/api-signup.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify(data)
        })
        .then(response => response.json())
        .then(result => {
            if (result.success) {
                showMessage(result.message, 'success');
                setTimeout(() => {
                    window.location.href = result.redirect;
                }, 1500);
            } else {
                showMessage(result.message, 'error');
                signupSubmit.disabled = false;
                signupSubmit.innerText = originalText;
            }
        })
        .catch(error => {
            console.error('Errore:', error);
            showMessage('Errore di connessione. Riprova piu tardi.', 'error');
            signupSubmit.disabled = false;
            signupSubmit.innerText = originalText;
        });
    });

    function showMessage(message, type) {
        var messageBox = document.getElementById('error-message');
        if (!messageBox) {
            console.error('Contenitore messaggi non trovato.');
            return;
        }
        messageBox.innerText = message;
        messageBox.className = type === 'success' ? 'alert alert-success' : 'alert alert-danger';
        messageBox.style.display = 'block';
    }
});
