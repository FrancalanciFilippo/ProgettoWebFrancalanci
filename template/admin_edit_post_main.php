<div class="container py-5 flex-grow-1 d-flex flex-column min-vh-100">
        <div class="row justify-content-center">
            <div class="col-12">
        
        <div class="mb-4">
            <h1 class="h1 fw-bold mb-1">Modifica Post</h1>
            <p class="text-secondary">
                Moderazione del post: <strong id="edit-post-title">Caricamento...</strong>
            </p>
        </div>

        
        <div class="card">
            <div class="card-body p-4">
                <form id="edit-post-form" action="#" method="post">
                    <input type="hidden" name="redirect_url" value="admin_posts.php" />
                    
                    
                    <h2 class="h5 fw-bold mb-3">Informazioni Generali</h2>
                    
                    <div class="mb-4">
                        <label for="luogoIncontro" class="form-label fw-medium">Luogo dell'incontro <span class="text-danger">*</span></label>
                        <input type="text" class="form-control focus-ring" id="luogoIncontro" name="luogo" 
                            value="" required="required" placeholder="Caricamento..." />
                    </div>

                    <hr class="my-4 text-secondary opacity-25" />

                    
                    <h2 class="h5 fw-bold mb-3">Pianificazione</h2>
                    
                    <div class="row g-3 mb-4">
                        <div class="col-sm-6">
                            <label for="dataInizio" class="form-label fw-medium">Da (Inizio) <span class="text-danger">*</span></label>
                            <input type="date" class="form-control focus-ring" id="dataInizio" name="data_inizio" 
                                value="" required="required" />
                        </div>
                        <div class="col-sm-6">
                            <label for="dataFine" class="form-label fw-medium text-dark">A (Fine)</label>
                            <input type="date" class="form-control focus-ring" id="dataFine" name="data_fine" 
                                value="" />
                        </div>
                    </div>

                    <hr class="my-4 text-secondary opacity-25" />

                    <div class="mb-4">
                        <label for="descrizionePost" class="form-label fw-medium">Descrizione post</label>
                        <textarea class="form-control focus-ring" id="descrizionePost" name="descrizione" rows="4" placeholder="Caricamento..."></textarea>
                    </div>

                    <hr class="my-4 text-secondary opacity-25" />

                    
                    <div class="mb-4">
                        <label class="form-label fw-medium text-dark">Partecipanti iscritti</label>
                        <p class="text-secondary small mb-3">Come amministratore puoi rimuovere iscritti da questa sessione.</p>
                        <div class="list-group border rounded-3" id="partecipanti">
                            <div class="list-group-item text-muted small py-3">Caricamento...</div>
                        </div>
                    </div>

                    
                    <div class="d-flex flex-column flex-sm-row justify-content-end gap-2 mt-5">
                        <a href="admin_posts.php" class="btn btn-outline-secondary px-4 fw-semibold order-2 order-sm-1">
                            Annulla
                        </a>
                        <button type="submit" id="edit-post-submit" class="btn btn-warning px-5 fw-bold text-dark order-1 order-sm-2">
                            <em class="bi bi-save me-2"></em>Salva Modifiche
                        </button>
                    </div>

                </form>
            </div>
        </div>
            </div>
        </div>
    </div>
