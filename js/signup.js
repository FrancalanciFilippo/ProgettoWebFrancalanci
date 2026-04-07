document.addEventListener('DOMContentLoaded', function() {
    const signupForm = document.getElementById('signup-form');
    const signupSubmit = document.getElementById('signup-submit');
    
    if (!signupForm) return;
    
    signupForm.addEventListener('submit', async function(e) {
        e.preventDefault();
        
        const nome = document.getElementById('signup-name').value.trim();
        const cognome = document.getElementById('signup-surname').value.trim();
        const email = document.getElementById('signup-email').value.trim();
        const password = document.getElementById('signup-password').value.trim();
        const password_confirm = document.getElementById('signup-password-confirm').value.trim();
        const bio = document.getElementById('signup-bio').value.trim();
        
        if (!nome || !cognome || !email || !password || !password_confirm) {
            showMessage('Tutti i campi obbligatori devono essere compilati.', 'error');
            return;
        }
        
        const data = { nome, cognome, email, password, password_confirm, bio };
        
        signupSubmit.disabled = true;
        const originalText = signupSubmit.innerText;
        signupSubmit.innerText = 'Registrazione in corso...';
        
        try {
            const response = await fetch('../ajax/login/api-signup.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify(data)
            });
            
            const contentType = response.headers.get('content-type');
            if (!contentType || !contentType.includes('application/json')) {
                const text = await response.text();
                throw new Error(`Risposta non JSON: ${text.substring(0, 100)}`);
            }
            
            const result = await response.json();
            
            if (result.success) {
                showMessage('Account creato con successo! Reindirizzamento al login...', 'success');
                setTimeout(() => {
                    window.location.href = result.redirect;
                }, 1500);
            } else {
                showMessage(result.message, 'error');
                signupSubmit.disabled = false;
                signupSubmit.innerText = originalText;
            }
        } catch (error) {
            console.error('Errore:', error);
            showMessage('Errore di connessione. Riprova più tardi.', 'error');
            signupSubmit.disabled = false;
            signupSubmit.innerText = originalText;
        }
    });
    
    function showMessage(message, type) {
        let messageBox = document.getElementById('error-message');
        if (!messageBox) {
            console.error('Contenitore messaggi non trovato.');
            return;
        }
        messageBox.innerText = message;
        messageBox.className = type === 'success' ? 'alert alert-success' : 'alert alert-danger';
        messageBox.style.display = 'block';
    }
});
