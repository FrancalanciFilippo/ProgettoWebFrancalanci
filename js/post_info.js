document.addEventListener("DOMContentLoaded", () => {
    const urlParams = new URLSearchParams(window.location.search);
    const postId = urlParams.get('id');

    if (!postId) {
        console.error("ID post non trovato nell'URL");
        return;
    }

    fetch(`../ajax/posts/api-post-info.php?id=${postId}`)
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            renderPostInfo(data.post);
        } else {
            console.error("Errore nel caricamento del post:", data.message);
        }
    })
    .catch(error => {
        console.error("Errore durante la fetch del post:", error);
    });


    document.addEventListener('click', event => {
        const btn = event.target.closest('[data-post-id]');
        if (btn) {
            event.preventDefault();
            const postId = parseInt(btn.dataset.postId);
            partecipaPost(postId);
        }
    });
});

function renderPostInfo(post) {
    const formatDateTime = (dateString) => {
        if (!dateString) return 'N/A';
        const date = new Date(dateString);
        if (isNaN(date.getTime())) return 'Data non valida';
        
        return date.toLocaleString('it-IT', {
            day: '2-digit',
            month: '2-digit',
            year: 'numeric',
            hour: '2-digit',
            minute: '2-digit'
        });
    };


    const titleEl = document.getElementById('post-title');
    if (titleEl) titleEl.textContent = post.titolo;


    const iconEl = document.getElementById('post-type-icon');
    if (iconEl) {
        iconEl.className = post.tipo === 'progettuale' ? 'bi bi-diagram-3 fs-3' : 'bi bi-book fs-3';
        iconEl.style.color = 'var(--color-primary)';
    }


    const authorEl = document.getElementById('post-author');
    if (authorEl) {
        const authorName = `${post.creatore_nome} ${post.creatore_cognome}`;
        authorEl.innerHTML = `
            <em class="bi bi-person-circle fs-5 me-2"></em>
            <span>Pubblicato da <strong>${authorName}</strong></span>
            <span class="ms-3"><em class="bi bi-clock me-1"></em>${formatDateTime(post.data_creazione)}</span>
        `;
    }


    const materiaEl = document.getElementById('post-materia');
    if (materiaEl) materiaEl.textContent = post.materia_nome;


    const partecipantiEl = document.getElementById('post-partecipanti');
    if (partecipantiEl) partecipantiEl.textContent = `${post.partecipanti_attuali || 0} / ${post.max_partecipanti}`;


    const periodoEl = document.getElementById('post-periodo');
    if (periodoEl) {
        const dataInizio = post.data_inizio ? new Date(post.data_inizio).toLocaleDateString('it-IT') : 'N/A';
        const dataFine = post.data_fine ? new Date(post.data_fine).toLocaleDateString('it-IT') : 'N/A';
        periodoEl.textContent = `${dataInizio} – ${dataFine}`;
    }


    const luogoEl = document.getElementById('post-luogo');
    if (luogoEl) luogoEl.textContent = post.luogo || 'Non specificato';


    const approvazioneEl = document.getElementById('post-approvazione');
    if (approvazioneEl) {
        const richiedeApprovazione = parseInt(post.richiede_approvazione, 10) === 1;
        approvazioneEl.textContent = richiedeApprovazione ? 'Richiesta' : 'Non richiesta (partecipazione diretta)';
    }


    const tipoEl = document.getElementById('post-tipo');
    if (tipoEl) tipoEl.textContent = post.tipo === 'progettuale' ? 'Progetto di gruppo' : 'Sessione di studio';


    const descrizioneEl = document.getElementById('post-descrizione');
    if (descrizioneEl) descrizioneEl.innerHTML = post.post_descrizione;


    const buttonEl = document.getElementById('post-action-btn');
    if (buttonEl) {
        const richiedeApprovazione = parseInt(post.richiede_approvazione, 10) === 1;
        buttonEl.setAttribute('data-post-id', post.id);
        buttonEl.innerHTML = `
            <em class="bi bi-person-plus me-2" aria-hidden="true"></em>${richiedeApprovazione ? 'Invia richiesta' : 'Partecipa'}
        `;
    }
}

function partecipaPost(postId) {
    const formData = new FormData();
    formData.append('post_id', postId);

    fetch('../ajax/posts/api-partecipa.php', {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            alert('Partecipazione avvenuta con successo!');
            window.location.href = 'joined_posts.php';
        } else {
            alert(data.message || 'Errore durante la partecipazione.');
        }
    })
    .catch(error => {
        console.error('Errore durante la partecipazione:', error);
        alert('Errore di connessione. Riprova.');
    });
}