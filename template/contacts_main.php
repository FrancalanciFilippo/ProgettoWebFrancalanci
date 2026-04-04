<section class="py-5 blur-entrance">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-8">

                <h1 class="h2 fw-bold mb-4">Contattaci</h1>

                <!-- Info di contatto -->
                <div class="row g-3 mb-5">
                    <div class="col-12 col-sm-4">
                        <div class="card border-0 shadow-sm h-100 text-center">
                            <div class="card-body p-4">
                                <em class="bi bi-envelope-at fs-2 d-block mb-2" style="color: var(--color-primary);"></em>
                                <h2 class="h6 fw-bold mb-1">Email</h2>
                                <a href="mailto:filippo.francalanci@studio.unibo.it" class="text-secondary small text-decoration-none">filippo.francalanci@studio.unibo.it</a>
                            </div>
                        </div>
                    </div>
                    <div class="col-12 col-sm-4">
                        <div class="card border-0 shadow-sm h-100 text-center">
                            <div class="card-body p-4">
                                <em class="bi bi-geo-alt fs-2 d-block mb-2" style="color: var(--color-primary);"></em>
                                <h2 class="h6 fw-bold mb-1">Sede</h2>
                                <span class="text-secondary small">Universit&agrave; di Bologna</span>
                            </div>
                        </div>
                    </div>
                    <div class="col-12 col-sm-4">
                        <div class="card border-0 shadow-sm h-100 text-center">
                            <div class="card-body p-4">
                                <em class="bi bi-clock fs-2 d-block mb-2" style="color: var(--color-primary);"></em>
                                <h2 class="h6 fw-bold mb-1">Orari</h2>
                                <span class="text-secondary small">Lun - Ven, 9:00 - 18:00</span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Form contatti -->
                <div class="card border-0 shadow-sm">
                    <div class="card-body p-4 p-md-5">

                        <h2 class="h5 fw-bold mb-1">Inviaci un messaggio</h2>
                        <p class="text-secondary small mb-4">Compila il form e ti risponderemo il prima possibile.</p>

                        <form action="#" method="post" id="contact-form" novalidate="novalidate">

                            <div class="row g-3 mb-3">
                                <div class="col-sm-6">
                                    <label for="contact-name" class="form-label fw-semibold">Nome</label>
                                    <input type="text" class="form-control" id="contact-name" name="name"
                                        placeholder="Il tuo nome" required="required" maxlength="50" autocomplete="given-name" />
                                </div>
                                <div class="col-sm-6">
                                    <label for="contact-surname" class="form-label fw-semibold">Cognome</label>
                                    <input type="text" class="form-control" id="contact-surname" name="surname"
                                        placeholder="Il tuo cognome" required="required" maxlength="50" autocomplete="family-name" />
                                </div>
                            </div>

                            <div class="mb-3">
                                <label for="contact-email" class="form-label fw-semibold">Email</label>
                                <input type="email" class="form-control" id="contact-email" name="email"
                                    placeholder="nome@esempio.com" required="required" maxlength="100" autocomplete="email" />
                            </div>

                            <div class="mb-3">
                                <label for="contact-subject" class="form-label fw-semibold">Oggetto</label>
                                <select class="form-select" id="contact-subject" name="subject" required="required">
                                    <option value="" selected="selected" disabled="disabled">Seleziona un argomento</option>
                                    <option value="supporto">Supporto tecnico</option>
                                    <option value="info">Informazioni generali</option>
                                    <option value="collaborazioni">Collaborazioni</option>
                                    <option value="segnalazione">Segnalazione bug</option>
                                    <option value="altro">Altro</option>
                                </select>
                            </div>

                            <div class="mb-4">
                                <label for="contact-message" class="form-label fw-semibold">Messaggio</label>
                                <textarea class="form-control" id="contact-message" name="message" rows="5"
                                    placeholder="Scrivi qui il tuo messaggio..." required="required"></textarea>
                            </div>

                            <div class="d-grid">
                                <button type="submit" class="btn btn-custom-primary fw-bold">
                                    <em class="bi bi-send me-2" aria-hidden="true"></em>Invia Messaggio
                                </button>
                            </div>

                        </form>
                    </div>
                </div>

            </div>
        </div>
    </div>
</section>
