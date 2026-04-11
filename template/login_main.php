<div class="container py-5">
        <div class="row justify-content-center">
            <div class="col-12 col-sm-10 col-md-7 col-lg-5">

                <div class="mb-4 text-center">
                    <h1 id="login-title" class="h1 fw-bold mb-1">Login</h1>
                    <p class="text-secondary">Accedi al tuo account SchoolTogether</p>
                </div>

                <div class="card shadow-sm">
                    <div class="card-body p-4">

                        <form action="#" method="post" id="login-form" novalidate="novalidate">

                            <div class="mb-3">
                                <label for="email" class="form-label fw-semibold">Email</label>
                                <input type="email" class="form-control" id="email" name="email"
                                    placeholder="nome@example.com" required="required" autocomplete="email" />
                            </div>

                            <div class="mb-4">
                                <label for="password" class="form-label fw-semibold">Password</label>
                                <input type="password" class="form-control" id="password" name="password"
                                    placeholder="Password" required="required"
                                    autocomplete="current-password" />
                            </div>

                            <div class="d-grid">
                                <button type="submit" class="btn btn-custom-primary" id="login-submit">
                                    Accedi
                                </button>
                            </div>

                        </form>
                    </div>
                </div>

                <p class="text-center text-secondary small mt-3">
                    Non hai un account?
                    <a href="signup.php">Creane uno qui</a>
                </p>

            </div>
        </div>
    </div>
