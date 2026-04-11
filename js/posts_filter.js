document.addEventListener("DOMContentLoaded", () => {
    loadMaterie();
    
    syncFormValues();
});

function loadMaterie() {
    fetch('../ajax/posts/api-materie.php')
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            populateMateriaDropdowns(data.materie);
        } else {
            console.error("Errore nel caricamento delle materie:", data.message);
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

        desktopSelect.innerHTML = '<option value="">Tutte le materie</option>';
        materie.forEach(materia => {
            const option = document.createElement('option');
            option.value = materia.nome.toLowerCase();
            option.textContent = materia.nome;

            if (window.currentFilters && window.currentFilters.subject === materia.nome.toLowerCase()) {
                option.selected = true;
            }
            desktopSelect.appendChild(option);
        });
    }

    if (mobileSelect) {

        mobileSelect.innerHTML = '<option value="">Tutte le materie</option>';
        materie.forEach(materia => {
            const option = document.createElement('option');
            option.value = materia.nome.toLowerCase();
            option.textContent = materia.nome;

            if (window.currentFilters && window.currentFilters.subject === materia.nome.toLowerCase()) {
                option.selected = true;
            }
            mobileSelect.appendChild(option);
        });
    }
}

function syncFormValues() {

    if (window.currentFilters) {

        if (window.currentFilters.sort) {
            const desktopSort = document.getElementById('sort-desktop');
            const mobileSort = document.getElementById('sort-mobile');
            if (desktopSort) desktopSort.value = window.currentFilters.sort;
            if (mobileSort) mobileSort.value = window.currentFilters.sort;
        }
        

        if (window.currentFilters.date_from) {
            const desktopDate = document.getElementById('date-desktop');
            const mobileDate = document.getElementById('date-mobile');
            if (desktopDate) desktopDate.value = window.currentFilters.date_from;
            if (mobileDate) mobileDate.value = window.currentFilters.date_from;
        }


        if (window.currentFilters.type) {
            const desktopType = document.getElementById('type-desktop');
            const mobileType = document.getElementById('type-mobile');
            if (desktopType) desktopType.value = window.currentFilters.type;
            if (mobileType) mobileType.value = window.currentFilters.type;
        }
        
        
        if (window.currentFilters.show_unavailable) {
            const desktopShowUnavail = document.getElementById('show-unavailable-desktop');
            const mobileShowUnavail = document.getElementById('show-unavailable-mobile');
            if (desktopShowUnavail) desktopShowUnavail.checked = true;
            if (mobileShowUnavail) mobileShowUnavail.checked = true;
        }
    }


    const resetDesktop = document.getElementById('reset-filters-desktop');
    const resetMobile = document.getElementById('reset-filters-mobile');

    if (resetDesktop) {
        resetDesktop.addEventListener('click', () => {
            resetFilters();
        });
    }

    if (resetMobile) {
        resetMobile.addEventListener('click', () => {
            resetFilters();
        });
    }
}

function resetFilters() {
    const formDesktop = document.getElementById('filter-form-desktop');
    if (formDesktop) {
        formDesktop.reset();
    }

    const formMobile = document.getElementById('filter-form-mobile');
    if (formMobile) {
        formMobile.reset();
    }

    window.location.href = window.location.pathname;
}