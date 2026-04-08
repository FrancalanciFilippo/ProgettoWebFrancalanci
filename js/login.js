document.addEventListener("DOMContentLoaded", () => {
    var loginForm = document.getElementById("login-form");
    var loginSubmit = document.getElementById("login-submit");

    if (!loginForm) return;

    loginForm.addEventListener("submit", event => {
        event.preventDefault();

        hideMessage();

        var email = document.getElementById("email").value.trim();
        var password = document.getElementById("password").value;

        if (!email || !password) {
            showMessage("Email e password sono obbligatori.", "error");
            return;
        }

        if (!isValidEmail(email)) {
            showMessage("Inserisci un'email valida.", "error");
            return;
        }

        setLoading(true);

        fetch('../ajax/login/api-login.php', {
            method: "POST",
            headers: { "Content-Type": "application/json" },
            body: JSON.stringify({ email: email, password: password })
        })
        .then(response => response.json())
        .then(result => {
            if (result.success) {
                showMessage(result.message, "success");
                setTimeout(() => {
                    window.location.href = result.redirect;
                }, 1000);
            } else {
                showMessage(result.message, "error");
                var passwordField = document.getElementById("password");
                if (passwordField) passwordField.focus();
                setLoading(false);
            }
        })
        .catch(error => {
            console.error("Errore login:", error);
            showMessage("Errore di connessione. Riprova piu tardi.", "error");
            setLoading(false);
        });
    });

    function showMessage(message, type) {
        var messageBox = document.getElementById("error-message");
        if (!messageBox) {
            console.error("Contenitore messaggi non trovato");
            return;
        }
        messageBox.textContent = message;
        messageBox.className = 'alert alert-' + (type === 'success' ? 'success' : 'danger');
        messageBox.style.display = 'block';
        messageBox.setAttribute('role', 'alert');
    }

    function hideMessage() {
        var messageBox = document.getElementById("error-message");
        if (messageBox) {
            messageBox.style.display = 'none';
            messageBox.textContent = '';
        }
    }

    function isValidEmail(email) {
        return /^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email);
    }

    function setLoading(isLoading) {
        if (loginSubmit) {
            loginSubmit.disabled = isLoading;
            loginSubmit.textContent = isLoading ? 'Accesso in corso...' : 'Accedi';
        }
        loginForm.querySelectorAll('input').forEach(input => {
            input.disabled = isLoading;
        });
    }
});