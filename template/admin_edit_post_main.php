<section class="py-5 blur-entrance">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-8">
        <!-- Header della pagina -->
        <div class="mb-4">
            <h1 class="fw-bold mb-1">Modifica Post</h1>
            <p class="text-secondary small">
                Moderazione del post: <strong><?php echo htmlspecialchars($templateParams['editPost']['titolo']); ?></strong>
            </p>
        </div>

        <!-- Form Card -->
        <div class="card shadow-sm">
            <div class="card-body p-4">
                <form id="edit-post-form" action="#" method="post" enctype="multipart/form-data">
                    <input type="hidden" name="id" value="<?php echo $templateParams['editPost']['id']; ?>" />
                    <input type="hidden" name="redirect_url" value="admin_posts.php" />
                    
                    <!-- Informazioni Modificabili -->
                    <h2 class="h5 fw-bold mb-3">Informazioni Generali</h2>
                    
                    <div class="mb-4">
                        <label for="luogoIncontro" class="form-label fw-medium">Luogo dell'incontro <span class="text-danger">*</span></label>
                        <input type="text" class="form-control focus-ring" id="luogoIncontro" name="luogo" 
                            value="<?php echo htmlspecialchars($templateParams['editPost']['luogo']); ?>" required="required" />
                    </div>

                    <hr class="my-4 text-secondary opacity-25" />

                    <!-- Sezione Date -->
                    <h2 class="h5 fw-bold mb-3">Pianificazione</h2>
                    
                    <div class="row g-3 mb-4">
                        <div class="col-sm-6">
                            <label for="dataInizio" class="form-label fw-medium">Da (Inizio) <span class="text-danger">*</span></label>
                            <input type="date" class="form-control focus-ring" id="dataInizio" name="data_inizio" 
                                value="<?php echo date('Y-m-d', strtotime($templateParams['editPost']['data_inizio'])); ?>" required="required" />
                        </div>
                        <div class="col-sm-6">
                            <label for="dataFine" class="form-label fw-medium text-dark">A (Fine)</label>
                            <input type="date" class="form-control focus-ring" id="dataFine" name="data_fine" 
                                value="<?php echo $templateParams['editPost']['data_fine'] ? date('Y-m-d', strtotime($templateParams['editPost']['data_fine'])) : ''; ?>" />
                        </div>
                    </div>

                    <hr class="my-4 text-secondary opacity-25" />

                    <!-- Dettagli e Materiali -->
                    <h2 class="h5 fw-bold mb-3">Contenuti e Materiali</h2>

                    <div class="mb-4">
                        <label class="form-label fw-medium text-dark">Materiali già caricati</label>
                        <div class="list-group border rounded-3 mb-3" id="existing-files-list">
                            <div class="list-group-item text-muted small py-3">Caricamento file esistenti...</div>
                        </div>
                        
                        <label for="formFileMultiple" class="form-label fw-medium text-dark">Carica nuovi file</label>
                        <input class="form-control focus-ring" type="file" id="formFileMultiple" name="materiali[]" multiple="multiple" />
                    </div>

                    <div class="mb-4">
                        <label for="descrizionePost" class="form-label fw-medium">Descrizione post</label>
                        <textarea class="form-control focus-ring" id="descrizionePost" name="descrizione" rows="4"><?php echo htmlspecialchars($templateParams['editPost']['descrizione']); ?></textarea>
                    </div>

                    <hr class="my-4 text-secondary opacity-25" />

                    <!-- Gestione Partecipanti -->
                    <div class="mb-4">
                        <label class="form-label fw-medium text-dark">Elenchi Partecipanti</label>
                        <p class="text-secondary small mb-3">Come amministratore puoi rimuovere iscritti da questa sessione.</p>
                        <div class="list-group border rounded-3" id="partecipanti">
                            <div class="list-group-item text-muted small py-3">In caricamento...</div>
                        </div>
                        <div id="edit-post-partecipanti" class="d-none">0/0</div>
                    </div>

                    <!-- Bottoni Salvataggio -->
                    <div class="d-flex flex-column flex-sm-row justify-content-end gap-2 mt-5">
                        <a href="admin_posts.php" class="btn btn-outline-secondary px-4 fw-semibold order-2 order-sm-1">
                            Annulla
                        </a>
                        <button type="submit" id="submit-post-btn" class="btn btn-warning px-5 fw-bold order-1 order-sm-2 text-dark">
                            <em class="bi bi-save me-2"></em>Salva Modifiche
                        </button>
                    </div>

                </form>
            </div>
        </div>
            </div>
        </div>
    </div>
</section>

<script>
    // Passiamo l'ID del post al JS per il caricamento iniziale
    window.editingPostId = <?php echo $templateParams['editPost']['id']; ?>;
</script>
