<form action="#" method="post" id="profile-form" novalidate="novalidate">

    <div class="row g-3 mb-3">
        <div class="col-sm-6">
            <label for="profile-name" class="form-label fw-semibold">Nome</label>
            <input type="text" class="form-control" id="profile-name" name="name"
                value="Marco" disabled="disabled" maxlength="32" autocomplete="given-name" />
        </div>
        <div class="col-sm-6">
            <label for="profile-surname" class="form-label fw-semibold">Cognome</label>
            <input type="text" class="form-control" id="profile-surname" name="surname"
                value="Rossi" disabled="disabled" maxlength="32" autocomplete="family-name" />
        </div>
    </div>

    <div class="mb-3">
        <label for="profile-email" class="form-label fw-semibold">E-mail</label>
        <input type="email" class="form-control" id="profile-email" name="email"
            value="Marco@miamail.com" disabled="disabled" maxlength="32"
            autocomplete="email" />
    </div>

    <div class="mb-4">
        <label for="profile-bio" class="form-label fw-semibold">Descrizione</label>
        <textarea class="form-control" id="profile-bio" name="bio" rows="5"
            disabled="disabled" maxlength="200">sono Marco e mi piace...</textarea>
    </div>

    <div class="d-flex flex-wrap justify-content-between align-items-center mt-4 gap-3">
        <button type="button" class="btn btn-custom-primary fw-semibold px-4" id="toggle-edit-btn">
            <em class="bi bi-pencil-square me-2" aria-hidden="true"></em>Modifica
        </button>
        <div class="d-flex gap-2">
            <a href="logout.php" class="btn btn-outline-custom-primary fw-semibold" id="profile-logout">
                <em class="bi bi-box-arrow-right me-2" aria-hidden="true"></em>Logout
            </a>
            <button type="button" class="btn btn-outline-danger fw-semibold" id="profile-elimina" data-bs-toggle="modal" data-bs-target="#confirmDeleteModal">
                <em class="bi bi-person-x me-2" aria-hidden="true"></em>Elimina Account
            </button>
        </div>
    </div>

</form>

<!-- Modal di conferma eliminazione account -->
<div class="modal fade" id="confirmDeleteModal" tabindex="-1" aria-labelledby="confirmDeleteLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="confirmDeleteLabel">Elimina Account</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p class="mb-0">
                    <strong>Sei sicuro di voler eliminare il tuo account?</strong>
                </p>
                <p class="text-muted small mt-2">
                    Questa azione è irreversibile. Tutti i tuoi dati verranno eliminati dal sistema.
                </p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal" id="confirmDeleteNoBtn">
                    No
                </button>
                <button type="button" class="btn btn-danger" id="confirmDeleteYesBtn">
                    Sì, elimina il mio account
                </button>
            </div>
        </div>
    </div>
</div>
