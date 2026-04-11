<div class="container py-5">
        <div class="row justify-content-center">
            <div class="col-lg-8">

                <div class="mb-4">
                    <h1 class="h1 fw-bold mb-1">Contattaci</h1>
                    <p class="text-secondary">Raggiungici per domande o suggerimenti</p>
                </div>

                
                <div class="card">
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
                                <button type="submit" id="contact-submit-btn" class="btn btn-custom-primary fw-bold">
                                    <em class="bi bi-send me-2" aria-hidden="true"></em>Invia Messaggio
                                </button>
                            </div>

                        </form>
                    </div>
                </div>

            </div>
        </div>
    </div>
