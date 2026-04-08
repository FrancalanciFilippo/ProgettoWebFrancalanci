<section class="py-5 blur-entrance">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-8">
        <!-- Header della pagina -->
        <div class="mb-4">
            <h1 class="fw-bold mb-2">Modifica il tuo post</h1>
            <p class="text-secondary">
                Aggiorna i dettagli della tua sessione di studio o del tuo progetto. Nota: alcuni campi non sono modificabili dopo la pubblicazione.
            </p>
        </div>

        <!-- Form Card -->
        <div class="card shadow-sm">
            <div class="card-body p-4">
                <form id="edit-post-form" action="#" method="post" enctype="multipart/form-data">
                    
                    <!-- Informazioni Modificabili -->
                    <h2 class="h5 fw-bold mb-3">Informazioni Generali</h2>
                    
                    <!-- Luogo dell'incontro (Editabile) -->
                    <div class="mb-4">
                        <label for="luogoIncontro" class="form-label fw-medium">Luogo dell'incontro <span class="text-danger">*</span></label>
                        <input type="text" class="form-control focus-ring" id="luogoIncontro" name="luogo" value="" required="required" placeholder="Caricamento..." />
                    </div>

                    <hr class="my-4 text-secondary opacity-25" />

                    <!-- Sezione Date -->
                    <h2 class="h5 fw-bold mb-3">Pianificazione</h2>
                    
                    <div class="row g-3 mb-4">
                        <div class="col-sm-6">
                            <label for="dataInizio" class="form-label fw-medium">Da (Inizio) <span class="text-danger">*</span></label>
                            <input type="date" class="form-control focus-ring" id="dataInizio" name="data_inizio" value="" required="required" />
                        </div>
                        <div class="col-sm-6">
                            <label for="dataFine" class="form-label fw-medium">A (Fine) <span class="text-danger">*</span></label>
                            <input type="date" class="form-control focus-ring" id="dataFine" name="data_fine" value="" required="required" />
                        </div>
                    </div>

                    <!-- Switch Richiesta Approvazione -->
                    <div class="mb-4 p-3 bg-light rounded-3 border">
                        <div class="form-check form-switch mb-0">
                            <input class="form-check-input" type="checkbox" role="switch" id="richiedeApprovazione" name="approvazione_richiesta" />
                            <label class="form-check-label fw-medium ms-1" for="richiedeApprovazione">
                                Richiedi la tua approvazione prima che un utente possa unirsi
                            </label>
                        </div>
                        <div class="form-text ms-5 mt-1">
                            Se attivato, riceverai una notifica quando qualcuno clicca "Partecipa" e potrai accettare o ignorare la richiesta (utile per i progetti con un numero chiuso molto ristretto).
                        </div>
                    </div>

                    <hr class="my-4 text-secondary opacity-25" />

                    <!-- Descrizione e Pubblicazione -->
                    <h2 class="h5 fw-bold mb-3">Dettagli</h2>

                    <!-- File Caricati e Gestione Materiali -->
                    <div class="mb-4">
                        <label class="form-label fw-medium">Materiali già caricati</label>
                        <div class="list-group list-group-flush border rounded-3 mb-3" id="existing-files-list">
                            <span class="text-muted small p-2">Caricamento...</span>
                        </div>
                        
                        <label for="formFileMultiple" class="form-label fw-medium">Aggiungi altri file</label>
                        <input class="form-control" type="file" id="formFileMultiple" name="materiali[]" multiple="multiple" />
                        <div class="form-text mt-1">
                            Puoi caricare altri documenti per integrare il materiale già presente.
                        </div>
                    </div>
                    
                    <div class="mb-4">
                        <label for="descrizionePost" class="form-label fw-medium">Descrizione e materiale aggiuntivo</label>
                        <textarea class="form-control focus-ring" id="descrizionePost" name="descrizione" rows="4" placeholder="Caricamento..."></textarea>
                    </div>

                    <!-- Bottoni Salvataggio -->
                    <div class="d-flex flex-column flex-sm-row justify-content-end gap-2 mt-5">
                        <a href="my_posts.php" class="btn btn-outline-secondary px-4 fw-semibold order-2 order-sm-1">
                            Annulla
                        </a>
                        <button type="submit" class="btn btn-custom-primary px-5 fw-bold order-1 order-sm-2">
                            <em class="bi bi-save me-2" aria-hidden="true"></em>Salva Modifiche
                        </button>
                    </div>

                </form>
            </div>
        </div>
            </div>
        </div>
    </div>
</section>
