document.addEventListener("DOMContentLoaded", () => {
    var loginForm = document.getElementById("login-form");
    var loginSubmit = document.getElementById("login-submit");

    if (!loginForm) return;

    loginForm.addEventListener("submit", event => {
        event.preventDefault();

        var email = document.getElementById("email").value.trim();
        var password = document.getElementById("password").value;

        if (!email || !password) {
            alert("Email e password sono obbligatori.");
            return;
        }

        if (!isValidEmail(email)) {
            alert("Inserisci un'email valida.");
            return;
        }

        setLoading(true);

        const formData = new FormData();
        formData.append('email', email);
        formData.append('password', password);

        fetch('../ajax/login/api-login.php', {
            method: "POST",
            body: formData
        })
        .then(response => response.json())
        .then(result => {
            if (result.success) {
                window.location.href = result.redirect;
            } else {
                alert(result.message);
                var passwordField = document.getElementById("password");
                if (passwordField) passwordField.focus();
                setLoading(false);
            }
        })
        .catch(error => {
            console.error("Errore login:", error);
            alert("Errore di connessione. Riprova piu tardi.");
            setLoading(false);
        });
    });

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