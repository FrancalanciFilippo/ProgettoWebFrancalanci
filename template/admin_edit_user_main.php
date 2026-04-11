<div class="container py-5 flex-grow-1 d-flex flex-column min-vh-100">
        <div class="row justify-content-center">
            <div class="col-12">
                <div class="mb-4">
                    <h1 class="h1 fw-bold mb-1">Modifica Utente</h1>
                    <p class="text-secondary">Stai modificando il profilo di <strong id="edit-user-email">Caricamento...</strong></p>
                </div>

                <div class="card">
                    <div class="card-body p-4">
                        <form id="admin-edit-user-form" novalidate="novalidate">
                            
                            <div class="row g-3 mb-3">
                                <div class="col-sm-6">
                                    <label for="name" class="form-label fw-semibold">Nome</label>
                                    <input type="text" class="form-control" id="name" name="name" 
                                        value="" required="required" maxlength="32" placeholder="Caricamento..." />
                                </div>
                                <div class="col-sm-6">
                                    <label for="surname" class="form-label fw-semibold">Cognome</label>
                                    <input type="text" class="form-control" id="surname" name="surname" 
                                        value="" required="required" maxlength="32" placeholder="Caricamento..." />
                                </div>
                            </div>

                            <div class="mb-3">
                                <label for="email" class="form-label fw-semibold">E-mail</label>
                                <input type="email" class="form-control" id="email" name="email" 
                                    value="" required="required" maxlength="255" placeholder="Caricamento..." />
                            </div>

                            <div class="mb-4">
                                <label for="bio" class="form-label fw-semibold">Descrizione</label>
                                <textarea class="form-control" id="bio" name="bio" rows="5" 
                                    maxlength="200" placeholder="Caricamento..."></textarea>
                            </div>

                            <div class="d-flex flex-column flex-sm-row justify-content-end gap-2 mt-4">
                                <a href="admin_users.php" class="btn btn-outline-secondary px-4 fw-semibold order-2 order-sm-1">
                                    Annulla
                                </a>
                                <button type="submit" class="btn btn-warning fw-bold px-5 text-dark order-1 order-sm-2" id="save-user-btn">
                                    <em class="bi bi-save me-2"></em>Salva Modifiche
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
