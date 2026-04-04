document.addEventListener("DOMContentLoaded", function () {
    // Carica le materie dinamicamente
    loadMaterie();
    
    // Inizializza la sincronizzazione dei form con i valori correnti
    syncFormValues();
});

function loadMaterie() {
    fetch('../ajax/posts/api-materie.php')
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            populateMateriaDropdowns(data.materie);
        } else {
            console.error("Errore nel caricamento delle materie:", data.error);
        }
    })
    .catch(error => {
        console.error("Errore durante la fetch delle materie:", error);
    });
}

function populateMateriaDropdowns(materie) {
    const desktopSelect = document.getElementById('subject-desktop');
    const mobileSelect = document.getElementById('subject-mobile');

    if (desktopSelect) {
        // Mantieni l'opzione "Tutte le materie"
        desktopSelect.innerHTML = '<option value="">Tutte le materie</option>';
        materie.forEach(materia => {
            const option = document.createElement('option');
            option.value = materia.nome.toLowerCase(); // Usa il nome in minuscolo come valore
            option.textContent = materia.nome;
            // Seleziona l'opzione se corrisponde al filtro corrente
            if (window.currentFilters && window.currentFilters.subject === materia.nome.toLowerCase()) {
                option.selected = true;
            }
            desktopSelect.appendChild(option);
        });
    }

    if (mobileSelect) {
        // Mantieni l'opzione "Tutte le materie"
        mobileSelect.innerHTML = '<option value="">Tutte le materie</option>';
        materie.forEach(materia => {
            const option = document.createElement('option');
            option.value = materia.nome.toLowerCase(); // Usa il nome in minuscolo come valore
            option.textContent = materia.nome;
            // Seleziona l'opzione se corrisponde al filtro corrente
            if (window.currentFilters && window.currentFilters.subject === materia.nome.toLowerCase()) {
                option.selected = true;
            }
            mobileSelect.appendChild(option);
        });
    }
}

function syncFormValues() {
    // Sincronizza i valori dai filtri correnti nell'URL
    if (window.currentFilters) {
        // Sort
        if (window.currentFilters.sort) {
            const desktopSort = document.getElementById('sort-desktop');
            const mobileSort = document.getElementById('sort-mobile');
            if (desktopSort) desktopSort.value = window.currentFilters.sort;
            if (mobileSort) mobileSort.value = window.currentFilters.sort;
        }
        
        // Date
        if (window.currentFilters.date_from) {
            const desktopDate = document.getElementById('date-desktop');
            const mobileDate = document.getElementById('date-mobile');
            if (desktopDate) desktopDate.value = window.currentFilters.date_from;
            if (mobileDate) mobileDate.value = window.currentFilters.date_from;
        }

        // Type
        if (window.currentFilters.type) {
            const desktopType = document.getElementById('type-desktop');
            const mobileType = document.getElementById('type-mobile');
            if (desktopType) desktopType.value = window.currentFilters.type;
            if (mobileType) mobileType.value = window.currentFilters.type;
        }
        
        // Checkboxes
        if (window.currentFilters.no_auth) {
            const desktopNoAuth = document.getElementById('no-auth-desktop');
            const mobileNoAuth = document.getElementById('no-auth-mobile');
            if (desktopNoAuth) desktopNoAuth.checked = true;
            if (mobileNoAuth) mobileNoAuth.checked = true;
        }
        
        if (window.currentFilters.show_unavailable) {
            const desktopShowUnavail = document.getElementById('show-unavailable-desktop');
            const mobileShowUnavail = document.getElementById('show-unavailable-mobile');
            if (desktopShowUnavail) desktopShowUnavail.checked = true;
            if (mobileShowUnavail) mobileShowUnavail.checked = true;
        }
    }

    // Aggiungi event listener per i bottoni reset
    const resetDesktop = document.getElementById('reset-filters-desktop');
    const resetMobile = document.getElementById('reset-filters-mobile');

    if (resetDesktop) {
        resetDesktop.addEventListener('click', function() {
            resetFilters();
        });
    }

    if (resetMobile) {
        resetMobile.addEventListener('click', function() {
            resetFilters();
        });
    }
}

function resetFilters() {
    // Reset form desktop
    const formDesktop = document.getElementById('filter-form-desktop');
    if (formDesktop) {
        formDesktop.reset();
    }

    // Reset form mobile
    const formMobile = document.getElementById('filter-form-mobile');
    if (formMobile) {
        formMobile.reset();
    }

    // Vai alla pagina senza parametri (reset filtri)
    window.location.href = window.location.pathname;
}