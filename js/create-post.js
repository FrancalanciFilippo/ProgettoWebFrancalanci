document.addEventListener("DOMContentLoaded", () => {
    fetchMaterie();
    initFormSubmit();
});
function fetchMaterie() {
    fetch('../ajax/posts/api-materie.php')
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            renderMaterie(data.materie);
        } else {
            console.error("Errore materie:", data.message);
        }
    })
    .catch(error => console.error("Errore fetch materie:", error));
}
function renderMaterie(materieArray) {
    const select = document.getElementById('materiaSelezionata');
    if (!select) return;

    select.innerHTML = '<option value="" selected>Seleziona materia...</option>';

    if (!materieArray || materieArray.length === 0) {
        select.innerHTML = '<option value="" disabled>Nessuna materia disponibile</option>';
        return;
    }

    materieArray.forEach(materia => {
        const option = document.createElement('option');
        option.value = materia.id;
        option.textContent = materia.nome;
        select.appendChild(option);
    });
}

function initFormSubmit() {
    const form = document.getElementById('create-post-form');
    if (!form) return;

    form.addEventListener('submit', event => {
        event.preventDefault();

        const btn = document.getElementById('create-post-submit');
        const oldText = btn.innerHTML;
        

        btn.disabled = true;
        btn.innerHTML = 'Pubblicazione...';


        const formData = new FormData(form);

        fetch('../ajax/posts/api-create-post.php', {
            method: 'POST',
            body: formData
        })
        .then(response => response.json())
        .then(result => {
            if (result.success) {
                alert(result.message);
                window.location.href = result.redirect || 'my_posts.php';
            } else {
                alert('Errore: ' + result.message);
                btn.disabled = false;
                btn.innerHTML = oldText;
            }
        })
        .catch(error => {
            console.error('Errore submit:', error);
            alert('Errore di connessione. Riprova.');
            btn.disabled = false;
            btn.innerHTML = oldText;
        });
    });
}