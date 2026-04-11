<div class="container py-5">
        <div class="row justify-content-center">
            <div class="col-12 col-md-8 col-lg-6">

                <div class="mb-4 text-center">
                    <h1 id="register-title" class="h1 fw-bold mb-1">Registrazione</h1>
                    <p class="text-secondary">Crea un nuovo account per iniziare</p>
                </div>

                <div class="card shadow-sm">
                    <div class="card-body p-4">

                        <form action="#" method="post" id="signup-form" novalidate="novalidate">

                            <div class="row g-3 mb-3">
                                <div class="col-sm-6">
                                    <label for="signup-name" class="form-label fw-semibold">Nome</label>
                                    <input type="text" class="form-control" id="signup-name" name="nome"
                                        placeholder="Mario" required="required" maxlength="100"
                                        autocomplete="given-name" />
                                </div>
                                <div class="col-sm-6">
                                    <label for="signup-surname" class="form-label fw-semibold">Cognome</label>
                                    <input type="text" class="form-control" id="signup-surname" name="cognome"
                                        placeholder="Rossi" required="required" maxlength="100"
                                        autocomplete="family-name" />
                                </div>
                            </div>

                            <div class="mb-3">
                                <label for="signup-email" class="form-label fw-semibold">E-mail</label>
                                <input type="email" class="form-control" id="signup-email" name="email"
                                    placeholder="user@example.com" required="required"
                                    autocomplete="email" />
                            </div>

                            <div class="mb-3">
                                <label for="signup-password" class="form-label fw-semibold">Password</label>
                                <input type="password" class="form-control" id="signup-password" name="password"
                                    placeholder="Almeno 8 caratteri" required="required" minlength="8"
                                    autocomplete="new-password" />
                            </div>

                            <div class="mb-3">
                                <label for="signup-password-confirm" class="form-label fw-semibold">Conferma Password</label>
                                <input type="password" class="form-control" id="signup-password-confirm" name="password_confirm"
                                    placeholder="Ripeti la password" required="required" minlength="8"
                                    autocomplete="new-password" />
                            </div>

                            <div class="mb-4">
                                <label for="signup-bio" class="form-label fw-semibold">Parlami di te</label>
                                <textarea class="form-control" id="signup-bio" name="bio" rows="4"
                                    placeholder="Sono..." maxlength="500"></textarea>
                            </div>

                            <div class="d-grid">
                                <button type="submit" class="btn btn-custom-primary" id="signup-submit">
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
