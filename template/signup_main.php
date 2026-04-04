<section class="py-5" aria-labelledby="register-title">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-12 col-md-8 col-lg-6">

                <h1 id="register-title" class="h2 fw-bold text-center mb-4">Registrazione</h1>

                <div class="card shadow-sm">
                    <div class="card-body p-4">
                        <form action="#" method="post" id="register-form" enctype="multipart/form-data"
                            novalidate="novalidate">

                            <div class="row g-3 mb-3">
                                <div class="col-sm-6">
                                    <label for="reg-name" class="form-label fw-semibold">Nome</label>
                                    <input type="text" class="form-control" id="reg-name" name="name"
                                        placeholder="Mario" required="required" maxlength="32"
                                        autocomplete="given-name" />
                                </div>
                                <div class="col-sm-6">
                                    <label for="reg-surname" class="form-label fw-semibold">Cognome</label>
                                    <input type="text" class="form-control" id="reg-surname" name="surname"
                                        placeholder="Rossi" required="required" maxlength="32"
                                        autocomplete="family-name" />
                                </div>
                            </div>

                            <div class="mb-3">
                                <label for="reg-email" class="form-label fw-semibold">E-mail</label>
                                <input type="email" class="form-control" id="reg-email" name="email"
                                    placeholder="user@example.com" required="required" maxlength="32"
                                    autocomplete="email" />
                            </div>

                            <div class="mb-3">
                                <label for="reg-password" class="form-label fw-semibold">Password</label>
                                <input type="password" class="form-control" id="reg-password" name="password"
                                    placeholder="password" required="required" maxlength="32"
                                    autocomplete="new-password" />
                            </div>

                            <div class="mb-4">
                                <label for="reg-bio" class="form-label fw-semibold">Parlami di te</label>
                                <textarea class="form-control" id="reg-bio" name="bio" rows="4"
                                    placeholder="Sono..." maxlength="200"></textarea>
                            </div>

                            <div class="d-grid">
                                <button type="submit" class="btn btn-custom-primary" id="register-submit">
                                    Registrati
                                </button>
                            </div>

                        </form>
                    </div>
                </div>

                <p class="text-center text-secondary small mt-3">
                    Hai gi&agrave; un account?
                    <a href="login.php">Accedi qui</a>
                </p>

            </div>
        </div>
    </div>
</section>
