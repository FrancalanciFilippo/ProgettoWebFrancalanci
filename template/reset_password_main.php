<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-lg-5">
            <div class="mb-4 text-center">
                    <h1 id="register-title" class="h1 fw-bold mb-1">Modifica Password</h1>
                    <p class="text-secondary">Inserisci la tua vecchia password e la nuova password.</p>
                </div>
            <div class="card">
                <div class="card-body p-4">

                    <form id="reset-password-form" novalidate="novalidate">
                        <div class="mb-3">
                            <label for="old-password" class="form-label fw-semibold">Vecchia Password</label>
                            <input type="password" class="form-control" id="old-password" name="old_password" 
                                required="required" maxlength="128" autocomplete="current-password" />
                        </div>

                        <div class="mb-3">
                            <label for="new-password" class="form-label fw-semibold">Nuova Password</label>
                            <input type="password" class="form-control" id="new-password" name="new_password" 
                                required="required" minlength="8" maxlength="128" autocomplete="new-password" />
                        </div>

                        <div class="mb-4">
                            <label for="confirm-password" class="form-label fw-semibold">Conferma Nuova Password</label>
                            <input type="password" class="form-control" id="confirm-password" name="confirm_password" 
                                required="required" minlength="8" maxlength="128" autocomplete="new-password" />
                        </div>

                        <div class="row g-2 mt-4">
                            <div class="col-12 col-sm-6">
                                <button type="submit" class="btn btn-warning w-100 fw-bold text-dark">
                                    <em class="bi bi-key me-2" aria-hidden="true"></em>Modifica Password
                                </button>
                            </div>
                            <div class="col-12 col-sm-6">
                                <a href="profile.php" class="btn btn-outline-secondary w-100 fw-semibold">
                                    Annulla
                                </a>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
