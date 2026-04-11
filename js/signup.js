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
            alert('Tutti i campi obbligatori devono essere compilati.');
            return;
        }

        signupSubmit.disabled = true;
        var originalText = signupSubmit.innerText;
        signupSubmit.innerText = 'Registrazione in corso...';

        const formData = new FormData();
        formData.append('nome', nome);
        formData.append('cognome', cognome);
        formData.append('email', email);
        formData.append('password', password);
        formData.append('password_confirm', password_confirm);
        formData.append('bio', bio);

        fetch('../ajax/login/api-signup.php', {
            method: 'POST',
            body: formData
        })
        .then(response => response.json())
        .then(result => {
            if (result.success) {
                window.location.href = result.redirect;
            } else {
                alert(result.message);
                signupSubmit.disabled = false;
                signupSubmit.innerText = originalText;
            }
        })
        .catch(error => {
            console.error('Errore:', error);
            alert('Errore di connessione. Riprova piu tardi.');
            signupSubmit.disabled = false;
            signupSubmit.innerText = originalText;
        });
    });
});
