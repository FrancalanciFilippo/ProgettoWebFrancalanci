<section class="py-5 blur-entrance">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-8">

                <!-- Toolbar -->
                <div class="d-flex justify-content-between align-items-center p-3 bg-light rounded-3 border mb-4">
                    <span class="fw-semibold text-dark">Preparazione Analisi 1</span>
                    <span class="badge text-bg-secondary">3 commenti</span>
                </div>

                <!-- Lista Commenti -->
                <div class="d-flex flex-column gap-3 mb-5" id="comments-list">

                    <!-- Commento 1 (senza risposta) -->
                    <div class="card border-0 shadow-sm">
                        <div class="card-body p-4">
                            <div class="d-flex align-items-start gap-3">
                                <div class="comment-avatar" aria-hidden="true" data-user="Luca Bianchi"></div>
                                <div class="flex-grow-1">
                                    <div class="d-flex justify-content-between align-items-center mb-1">
                                        <span class="fw-semibold text-dark">Luca Bianchi</span>
                                        <div class="d-flex align-items-center gap-2">
                                            <span class="text-secondary small">15 Apr 2026, 10:32</span>
                                            <button class="reply-btn" title="Rispondi"
                                                aria-label="Rispondi a Luca Bianchi"
                                                onclick="setReply('Luca Bianchi', '15 Apr 2026, 10:32', 'Qualcuno sa dove parcheggiare il PdF del Bramanti?')">
                                                <em class="bi bi-reply" aria-hidden="true"></em> Rispondi
                                            </button>
                                        </div>
                                    </div>
                                    <p class="mb-0 text-dark">Qualcuno sa dove parcheggiare il PdF del Bramanti?</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Commento 2 (con risposta al commento 1) -->
                    <div class="card border-0 shadow-sm">
                        <div class="card-body p-4">
                            <div class="d-flex align-items-start gap-3">
                                <div class="comment-avatar" aria-hidden="true" data-user="Anna Rossi"></div>
                                <div class="flex-grow-1">
                                    <div class="d-flex justify-content-between align-items-center mb-1">
                                        <span class="fw-semibold text-dark">Anna Rossi</span>
                                        <div class="d-flex align-items-center gap-2">
                                            <span class="text-secondary small">15 Apr 2026, 11:05</span>
                                            <button class="reply-btn" title="Rispondi"
                                                aria-label="Rispondi ad Anna Rossi"
                                                onclick="setReply('Anna Rossi', '15 Apr 2026, 11:05', 'Ho caricato il PDF nella sezione materiali del post!')">
                                                <em class="bi bi-reply" aria-hidden="true"></em> Rispondi
                                            </button>
                                        </div>
                                    </div>
                                    <!-- Quote del commento a cui si risponde -->
                                    <div class="comment-reply-quote mb-2">
                                        <span class="reply-author">Luca Bianchi</span>
                                        <span class="text-muted ms-2 small">15 Apr 2026, 10:32</span>
                                        <span class="d-block mt-1">Qualcuno sa dove parcheggiare il PdF del Bramanti?</span>
                                    </div>
                                    <p class="mb-0 text-dark">Ho caricato il PDF nella sezione materiali del post!</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Commento 3 -->
                    <div class="card border-0 shadow-sm">
                        <div class="card-body p-4">
                            <div class="d-flex align-items-start gap-3">
                                <div class="comment-avatar" aria-hidden="true" data-user="Marco (Tu)"></div>
                                <div class="flex-grow-1">
                                    <div class="d-flex justify-content-between align-items-center mb-1">
                                        <span class="fw-semibold text-dark">Marco (Tu)</span>
                                        <div class="d-flex align-items-center gap-2">
                                            <span class="text-secondary small">16 Apr 2026, 09:15</span>
                                            <button class="reply-btn" title="Rispondi"
                                                aria-label="Rispondi a Marco"
                                                onclick="setReply('Marco (Tu)', '16 Apr 2026, 09:15', 'Ci vediamo alle 14 come concordato, giusto?')">
                                                <em class="bi bi-reply" aria-hidden="true"></em> Rispondi
                                            </button>
                                        </div>
                                    </div>
                                    <p class="mb-0 text-dark">Ci vediamo alle 14 come concordato, giusto?</p>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>

                <!-- Form Nuovo Commento -->
                <div class="card border-0 shadow-sm">
                    <div class="card-body p-4">
                        <h2 class="h5 fw-bold mb-4">Scrivi un commento</h2>

                        <form action="#" method="post" id="comment-form" class="needs-validation" novalidate="novalidate">

                            <!-- Reply Preview (nascosto di default) -->
                            <div id="reply-preview-wrapper" class="d-none mb-3">
                                <div class="reply-preview">
                                    <div class="d-flex justify-content-between align-items-start mb-1">
                                        <div>
                                            <span class="reply-author" id="reply-author-name"></span>
                                            <span class="text-muted ms-2 small" id="reply-author-date"></span>
                                        </div>
                                        <button type="button" class="btn-close btn-sm" aria-label="Annulla risposta"
                                            onclick="clearReply()" style="font-size:0.6rem;"></button>
                                    </div>
                                    <span class="reply-text" id="reply-text-preview"></span>
                                </div>
                            </div>

                            <!-- Input nascosto per l'ID della risposta -->
                            <input type="hidden" id="reply-to" name="reply_to" value="" />

                            <div class="mb-3">
                                <label for="comment-text" class="form-label fw-medium">Il tuo commento</label>
                                <textarea class="form-control" id="comment-text" name="commento" rows="3"
                                    placeholder="Scrivi qui il tuo messaggio..." required="required"></textarea>
                                <div class="invalid-feedback">
                                    Per favore, inserisci un testo per il tuo commento prima di inviarlo.
                                </div>
                            </div>

                            <div class="d-flex justify-content-end gap-2">
                                <button type="button" class="btn btn-outline-secondary fw-semibold"
                                    onclick="clearReply()">Annulla</button>
                                <button type="submit" class="btn btn-custom-primary fw-semibold px-4">
                                    <em class="bi bi-send me-2" aria-hidden="true"></em>Invia
                                </button>
                            </div>

                        </form>
                    </div>
                </div>

            </div>
        </div>
    </div>
</section>
