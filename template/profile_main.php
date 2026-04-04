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

    <div class="mb-3">
        <label for="profile-password" class="form-label fw-semibold">Password</label>
        <input type="password" class="form-control" id="profile-password" name="password"
            value="password123" disabled="disabled" maxlength="32"
            autocomplete="current-password" />
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
            <button type="button" class="btn btn-outline-danger fw-semibold" id="profile-elimina">
                <em class="bi bi-person-x me-2" aria-hidden="true"></em>Elimina Account
            </button>
        </div>
    </div>

</form>
