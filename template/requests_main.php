<div class="mb-4">
    <h1 class="h2 fw-bold mb-1">Richieste</h1>
    <p class="text-secondary mb-0">Gestisci le richieste di partecipazione inviate e ricevute.</p>
</div>

<!-- Filtri sotto la nav (tabs + direzione) -->
<div class="d-flex flex-column flex-sm-row justify-content-between align-items-start align-items-sm-center gap-3 mb-4 p-3 bg-light rounded-3 border">

    <!-- Filtro Stato -->
    <div>
        <span class="small fw-semibold text-muted d-block mb-2">Stato</span>
        <div class="btn-group btn-group-sm" role="group" aria-label="Filtra per stato">
            <input type="radio" class="btn-check" name="filtro-stato" id="stato-tutte" checked="checked" />
            <label class="btn btn-outline-custom-primary" for="stato-tutte">Tutte</label>

            <input type="radio" class="btn-check" name="filtro-stato" id="stato-pending" />
            <label class="btn btn-outline-custom-primary" for="stato-pending">In attesa</label>

            <input type="radio" class="btn-check" name="filtro-stato" id="stato-accettate" />
            <label class="btn btn-outline-custom-primary" for="stato-accettate">Accettate</label>

            <input type="radio" class="btn-check" name="filtro-stato" id="stato-rifiutate" />
            <label class="btn btn-outline-custom-primary" for="stato-rifiutate">Rifiutate</label>
        </div>
    </div>

    <!-- Filtro Direzione -->
    <div>
        <span class="small fw-semibold text-muted d-block mb-2">Direzione</span>
        <div class="btn-group btn-group-sm" role="group" aria-label="Filtra per direzione">
            <input type="radio" class="btn-check" name="filtro-dir" id="dir-tutte" checked="checked" />
            <label class="btn btn-outline-custom-primary" for="dir-tutte">Tutte</label>

            <input type="radio" class="btn-check" name="filtro-dir" id="dir-inviate" />
            <label class="btn btn-outline-custom-primary" for="dir-inviate">Inviate</label>

            <input type="radio" class="btn-check" name="filtro-dir" id="dir-ricevute" />
            <label class="btn btn-outline-custom-primary" for="dir-ricevute">Ricevute</label>
        </div>
    </div>

</div>

<!-- Lista Richieste -->
<div class="d-flex flex-column gap-3">

    <!-- Richiesta 1: Ricevuta - In attesa -->
    <div class="card border-0 shadow-sm">
        <div class="card-body p-4">
            <div class="d-flex justify-content-between align-items-start mb-2">
                <div class="d-flex align-items-center gap-2">
                    <span class="badge rounded-pill text-bg-primary">
                        <em class="bi bi-arrow-down-circle me-1"></em>Ricevuta
                    </span>
                    <span class="badge rounded-pill text-dark" style="background-color:#ffc107;">
                        <em class="bi bi-hourglass-split me-1"></em>In attesa
                    </span>
                </div>
                <span class="text-muted small">02 Apr 2026, 15:30</span>
            </div>

            <h2 class="h6 fw-bold text-dark mb-1">Preparazione Analisi 1</h2>
            <p class="text-secondary small mb-3">
                <em class="bi bi-person-circle me-1"></em>
                <strong>Anna Rossi</strong> vuole partecipare al tuo post
            </p>

            <div class="d-flex gap-2">
                <button type="button" class="btn btn-sm btn-custom-primary fw-semibold px-3">
                    <em class="bi bi-check-lg me-1"></em>Accetta
                </button>
                <button type="button" class="btn btn-sm btn-outline-danger fw-semibold px-3">
                    <em class="bi bi-x-lg me-1"></em>Rifiuta
                </button>
            </div>
        </div>
    </div>

    <!-- Richiesta 2: Inviata - Accettata -->
    <div class="card border-0 shadow-sm">
        <div class="card-body p-4">
            <div class="d-flex justify-content-between align-items-start mb-2">
                <div class="d-flex align-items-center gap-2">
                    <span class="badge rounded-pill text-bg-primary">
                        <em class="bi bi-arrow-up-circle me-1"></em>Inviata
                    </span>
                    <span class="badge rounded-pill text-white" style="background-color:#43a047;">
                        <em class="bi bi-check-circle me-1"></em>Accettata
                    </span>
                </div>
                <span class="text-muted small">01 Apr 2026, 10:15</span>
            </div>

            <h2 class="h6 fw-bold text-dark mb-1">Sviluppo Web App Gestionale</h2>
            <p class="text-secondary small mb-0">
                <em class="bi bi-person-circle me-1"></em>
                La tua richiesta al post di <strong>Luca Bianchi</strong> &egrave; stata accettata
            </p>
        </div>
    </div>

    <!-- Richiesta 3: Inviata - Rifiutata -->
    <div class="card border-0 shadow-sm">
        <div class="card-body p-4">
            <div class="d-flex justify-content-between align-items-start mb-2">
                <div class="d-flex align-items-center gap-2">
                    <span class="badge rounded-pill text-bg-primary">
                        <em class="bi bi-arrow-up-circle me-1"></em>Inviata
                    </span>
                    <span class="badge rounded-pill text-white" style="background-color:#dc3545;">
                        <em class="bi bi-x-circle me-1"></em>Rifiutata
                    </span>
                </div>
                <span class="text-muted small">29 Mar 2026, 18:45</span>
            </div>

            <h2 class="h6 fw-bold text-dark mb-1">Ripasso Fisica Generale</h2>
            <p class="text-secondary small mb-0">
                <em class="bi bi-person-circle me-1"></em>
                La tua richiesta al post di <strong>Sara Verdi</strong> &egrave; stata rifiutata
            </p>
        </div>
    </div>

    <!-- Richiesta 4: Ricevuta - Accettata (già gestita) -->
    <div class="card border-0 shadow-sm">
        <div class="card-body p-4">
            <div class="d-flex justify-content-between align-items-start mb-2">
                <div class="d-flex align-items-center gap-2">
                    <span class="badge rounded-pill text-bg-primary">
                        <em class="bi bi-arrow-down-circle me-1"></em>Ricevuta
                    </span>
                    <span class="badge rounded-pill text-white" style="background-color:#43a047;">
                        <em class="bi bi-check-circle me-1"></em>Accettata
                    </span>
                </div>
                <span class="text-muted small">28 Mar 2026, 09:00</span>
            </div>

            <h2 class="h6 fw-bold text-dark mb-1">Preparazione Analisi 1</h2>
            <p class="text-secondary small mb-0">
                <em class="bi bi-person-circle me-1"></em>
                Hai accettato la richiesta di <strong>Marco Neri</strong>
            </p>
        </div>
    </div>

    <!-- Richiesta 5: Ricevuta - In attesa -->
    <div class="card border-0 shadow-sm">
        <div class="card-body p-4">
            <div class="d-flex justify-content-between align-items-start mb-2">
                <div class="d-flex align-items-center gap-2">
                    <span class="badge rounded-pill text-bg-primary">
                        <em class="bi bi-arrow-down-circle me-1"></em>Ricevuta
                    </span>
                    <span class="badge rounded-pill text-dark" style="background-color:#ffc107;">
                        <em class="bi bi-hourglass-split me-1"></em>In attesa
                    </span>
                </div>
                <span class="text-muted small">02 Apr 2026, 17:10</span>
            </div>

            <h2 class="h6 fw-bold text-dark mb-1">Preparazione Analisi 1</h2>
            <p class="text-secondary small mb-3">
                <em class="bi bi-person-circle me-1"></em>
                <strong>Giulia Conti</strong> vuole partecipare al tuo post
            </p>

            <div class="d-flex gap-2">
                <button type="button" class="btn btn-sm btn-custom-primary fw-semibold px-3">
                    <em class="bi bi-check-lg me-1"></em>Accetta
                </button>
                <button type="button" class="btn btn-sm btn-outline-danger fw-semibold px-3">
                    <em class="bi bi-x-lg me-1"></em>Rifiuta
                </button>
            </div>
        </div>
    </div>

    <!-- Messaggio se vuoto -->
    <div class="text-center p-5 bg-white rounded-4 border shadow-sm mt-3 d-none">
        <em class="bi bi-bell-slash display-4 text-muted mb-3" aria-hidden="true"></em>
        <h2 class="h5 fw-bold">Nessuna richiesta trovata</h2>
        <p class="text-secondary">Non ci sono richieste che corrispondono ai filtri selezionati.</p>
    </div>

</div>
