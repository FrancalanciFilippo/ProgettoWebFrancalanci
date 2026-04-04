<div class="mb-4 d-flex justify-content-between align-items-end">
    <div>
        <h1 class="h2 fw-bold mb-1">I tuoi post</h1>
        <p class="text-secondary mb-0">Gestisci le sessioni di studio e i progetti che hai creato.</p>
    </div>
</div>

<!-- Lista Post -->
<div class="d-flex flex-column gap-4">

    <!-- CARD 1: Sessione di studio -->
    <div class="card shadow-sm mb-4 border-0">
        <div class="card-body p-4">
            <!-- Titolo e Icona Tipo -->
            <div class="d-flex justify-content-between align-items-start mb-2">
                <h2 class="h5 fw-bold mb-0 text-dark">
                    Preparazione Analisi 1
                </h2>
                <em class="bi bi-book fs-4" style="color: var(--color-primary);" aria-hidden="true"></em>
            </div>
            
            <!-- Autore della pubblicazione -->
            <div class="d-flex align-items-center text-muted small mb-3">
                <em class="bi bi-person-circle fs-5 me-2"></em>
                <span>Pubblicato da <strong>Marco (Tu)</strong></span>
            </div>

            <hr class="my-3 text-secondary opacity-25" />

            <!-- Dettagli -->
            <div class="row g-3 mb-4">
                <div class="col-12 col-sm-6">
                    <div class="d-flex align-items-center text-secondary small">
                        <em class="bi bi-tag me-2" aria-hidden="true"></em>
                        <strong>Materia:</strong>&nbsp;Matematica
                    </div>
                </div>
                <div class="col-12 col-sm-6">
                    <div class="d-flex align-items-center text-secondary small">
                        <em class="bi bi-people me-2" aria-hidden="true"></em>
                        <strong>Partecipanti:</strong>&nbsp;4/10
                    </div>
                </div>
                <div class="col-12 col-sm-6">
                    <div class="d-flex align-items-center text-secondary small">
                        <em class="bi bi-calendar-event me-2" aria-hidden="true"></em>
                        <strong>Dal:</strong>&nbsp;12 Ott &ndash; <strong>Al:</strong>&nbsp;20 Ott
                    </div>
                </div>
                <div class="col-12 col-sm-6">
                    <div class="d-flex align-items-center text-secondary small">
                        <em class="bi bi-geo-alt me-2" aria-hidden="true"></em>
                        <strong>Luogo:</strong>&nbsp;Aula Studio Campus
                    </div>
                </div>
            </div>
            
            <!-- Aggiunta per I Miei Post: Descrizione e File -->
            <div class="p-3 bg-light rounded-3 mb-4">
                <h3 class="h6 fw-bold mb-2">Descrizione</h3>
                <p class="small text-secondary mb-3">
                    Sessione di ripasso intenso per l'imminente appello. Focalizzati sugli integrali doppi e le serie di Taylor. Portate il libro di testo.
                </p>
                <h3 class="h6 fw-bold mb-2">Materiali Allegati</h3>
                <a href="#" class="btn btn-sm btn-outline-secondary d-inline-flex align-items-center border-0 bg-white shadow-sm">
                    <em class="bi bi-file-earmark-pdf me-2 text-danger"></em> Appunti_Lezioni.pdf
                </a>
            </div>

            <!-- Bottoni e Data -->
            <div class="d-flex flex-wrap justify-content-between align-items-end mt-2">
                <div class="row gx-2 mb-2 mb-sm-0 w-100 align-items-center">
                    <div class="col-6 col-sm-5 col-md-4 col-xl-3">
                        <a href="edit_post.php" class="btn btn-custom-primary w-100 text-center fw-semibold">
                            <em class="bi bi-pencil-square me-1"></em>Modifica
                        </a>
                    </div>
                    <div class="col-6 col-sm-5 col-md-4 col-xl-3">
                        <button class="btn btn-outline-danger w-100 text-center fw-semibold">
                            <em class="bi bi-trash me-1"></em>Elimina
                        </button>
                    </div>
                </div>
                <div class="text-secondary small mt-3 mt-sm-0 w-100 text-end">
                    <em class="bi bi-clock me-1" aria-hidden="true"></em>Pubblicato il 02/04/2026
                </div>
            </div>
        </div>
    </div>

    <!-- Messaggio se vuoto (mostrato come esempio) -->
    <div class="text-center p-5 bg-white rounded-4 border shadow-sm mt-3 d-none">
        <em class="bi bi-journal-x display-4 text-muted mb-3" aria-hidden="true"></em>
        <h2 class="h5 fw-bold">Non hai ancora creato nessun post</h2>
        <p class="text-secondary">Aiuta gli altri e organizza un gruppo di studio ora stesso!</p>
        <a href="create_post.php" class="btn btn-custom-primary mt-2">Nuovo Post</a>
    </div>
    
</div>
