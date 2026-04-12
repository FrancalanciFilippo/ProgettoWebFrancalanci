<div class="container">
        <div class="mb-4">
    <h1 class="h1 fw-bold mb-1">Il tuo Profilo</h1>
    <p class="text-secondary">Gestisci le tue informazioni personali e le impostazioni dell'account.</p>
</div>

<div class="card">
    <div class="card-body p-4">
        <form action="#" method="post" id="profile-form" novalidate="novalidate">
            
            <div class="row g-3 mb-3">
                <div class="col-sm-6">
                    <label for="profile-name" class="form-label fw-semibold">Nome</label>
                    <input type="text" class="form-control" id="profile-name" name="name"
                        value="Caricamento..." disabled="disabled" maxlength="32" autocomplete="given-name" />
                </div>
                <div class="col-sm-6">
                    <label for="profile-surname" class="form-label fw-semibold">Cognome</label>
                    <input type="text" class="form-control" id="profile-surname" name="surname"
                        value="Caricamento..." disabled="disabled" maxlength="32" autocomplete="family-name" />
                </div>
            </div>

            <div class="mb-3">
                <label for="profile-email" class="form-label fw-semibold">E-mail</label>
                <input type="email" class="form-control" id="profile-email" name="email"
                    value="Caricamento..." disabled="disabled" maxlength="32"
                    autocomplete="email" />
            </div>

            <div class="mb-4">
                <label for="profile-bio" class="form-label fw-semibold">Descrizione</label>
                <textarea class="form-control" id="profile-bio" name="bio" rows="5"
                    disabled="disabled" maxlength="200" placeholder="Parlaci di te..."></textarea>
            </div>

            <div class="mt-4 row">
                <div class="col-12 d-flex flex-column flex-sm-row justify-content-start gap-2">
                <button type="button" class="btn btn-custom-primary fw-semibold" id="toggle-edit-btn">
                    <em class="bi bi-pencil-square me-2" aria-hidden="true"></em>Modifica
                </button>
                <a href="reset_password.php" class="btn btn-warning fw-semibold text-dark">
                    <em class="bi bi-key me-2" aria-hidden="true"></em>Cambia Password
                </a>
                <a href="logout.php" class="btn btn-outline-custom-primary fw-semibold" id="profile-logout">
                    <em class="bi bi-box-arrow-right me-2" aria-hidden="true"></em>Logout
                </a>
                <button type="button" class="btn btn-outline-danger fw-semibold" id="profile-elimina">
                    <em class="bi bi-person-x me-2" aria-hidden="true"></em>Elimina Account
                </button>
                </div>
            </div>

        </form>
    </div>
</div>
    </div>
