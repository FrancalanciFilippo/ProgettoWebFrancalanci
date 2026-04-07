document.addEventListener("DOMContentLoaded", function () {
    const loginForm = document.getElementById("login-form");
    const loginSubmit = document.getElementById("login-submit");

    if (!loginForm) return;

    loginForm.addEventListener("submit", async function (event) {
        event.preventDefault();

        hideMessage();

        const email = document.getElementById("email").value.trim();
        const password = document.getElementById("password").value;

        if (!email || !password) {
            showMessage("Email e password sono obbligatori.", "error");
            return;
        }

        if (!isValidEmail(email)) {
            showMessage("Inserisci un'email valida.", "error");
            return;
        }

        setLoading(true);

        try {
            const response = await fetch('../ajax/login/api-login.php', { 
                method: "POST",
                headers: { "Content-Type": "application/json" },
                body: JSON.stringify({ email, password })
            });

            const contentType = response.headers.get('content-type');
            if (!contentType || !contentType.includes('application/json')) {
                const text = await response.text();
                throw new Error(`Risposta non JSON: ${text.substring(0, 100)}`);
            }

            const result = await response.json();

            if (result.success) {
                showMessage(result.message, "success");
                setTimeout(() => {
                    window.location.href = result.redirect;
                }, 1000);
            } else {
                showMessage(result.message, "error");
                document.getElementById("password")?.focus();
            }
        } catch (error) {
            console.error("Errore login:", error);
            showMessage("Errore di connessione. Riprova più tardi.", "error");
        } finally {
            setLoading(false);
        }
    });

    function showMessage(message, type) {
        let messageBox = document.getElementById("error-message");
        if (!messageBox) {
            console.error("Contenitore messaggi non trovato");
            return;
        }
        messageBox.textContent = message;
        messageBox.className = `alert alert-${type === 'success' ? 'success' : 'danger'}`;
        messageBox.style.display = 'block';
        messageBox.setAttribute('role', 'alert');
    }
    
    function hideMessage() {
        const messageBox = document.getElementById("error-message");
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