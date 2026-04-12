document.addEventListener("DOMContentLoaded", () => {
    const form = document.getElementById("reset-password-form");
    if (!form) return;

    form.addEventListener("submit", event => {
        event.preventDefault();

        const oldPassword = document.getElementById("old-password").value.trim();
        const newPassword = document.getElementById("new-password").value.trim();
        const confirmPassword = document.getElementById("confirm-password").value.trim();

        if (!oldPassword || !newPassword || !confirmPassword) {
            alert("Tutti i campi sono obbligatori.");
            return;
        }

        if (newPassword.length < 8) {
            alert("La nuova password deve essere di almeno 8 caratteri.");
            return;
        }

        if (newPassword !== confirmPassword) {
            alert("La nuova password e la conferma non coincidono.");
            return;
        }

        const formData = new FormData();
        formData.append("old_password", oldPassword);
        formData.append("new_password", newPassword);

        fetch("../ajax/profile/api-reset-password.php", {
            method: "POST",
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                alert(data.message);
                window.location.href = "profile.php";
            } else {
                alert(data.message || "Errore durante la modifica della password.");
            }
        })
        .catch(error => {
            console.error("Errore:", error);
            alert("Errore di connessione. Riprova più tardi.");
        });
    });
});
