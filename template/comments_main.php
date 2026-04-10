<section class="py-5 blur-entrance">
    <div class="container">
        <div class="row justify-content-center">
            <div>

                <div class="d-flex justify-content-between align-items-center p-3 bg-light rounded-3 border mb-4">
                    <span class="fw-semibold text-dark" id="post-title">Caricamento...</span>
                    <span class="badge text-bg-secondary" id="comments-count">0 commenti</span>
                </div>

        <div class="mb-5" id="comments-list">
                        <p>Caricamento commenti in corso...</p>
                    </div>
                </div>

                <div class="card border-0 shadow-sm">
                    <div class="card-body p-4">
                        <h2 class="h5 fw-bold mb-4">Scrivi un commento</h2>

                        <form method="post" id="comment-form" class="needs-validation" novalidate="novalidate">

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

                            <input type="hidden" id="reply-to" name="reply_to" value="" />

                            <div class="mb-3">
                                <label for="comment-text" class="form-label fw-medium">Il tuo commento</label>
                                <textarea class="form-control focus-ring" id="comment-text" name="commento" rows="3"
                                    placeholder="Scrivi qui il tuo messaggio..." required="required"></textarea>
                                <div class="invalid-feedback">
                                    Per favore, inserisci un testo per il tuo commento prima di inviarlo.
                                </div>
                            </div>

                            <div class="d-flex justify-content-end gap-2">
                                <button type="button" class="btn btn-outline-secondary fw-semibold"
                                    onclick="clearReply()">Annulla Risposta</button>
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
