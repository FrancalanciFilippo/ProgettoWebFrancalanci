<?php $user = $templateParams["editUser"]; ?>
<section class="py-5 blur-entrance">
    <div class="container text-center">
        <div class="row justify-content-center">
            <div class="col-lg-8 text-start">
                <div class="mb-4">
                    <h1 class="h2 fw-bold mb-1">Modifica Utente</h1>
                    <p class="text-secondary mb-0">Stai modificando il profilo di <strong><?php echo htmlspecialchars($user['email']); ?></strong></p>
                </div>

                <div class="card border-0 shadow-sm">
                    <div class="card-body p-4">
                        <form id="admin-edit-user-form" novalidate="novalidate">
                            <input type="hidden" name="user_id" value="<?php echo $user['id']; ?>" />
                            
                            <div class="row g-3 mb-3">
                                <div class="col-sm-6">
                                    <label for="name" class="form-label fw-semibold">Nome</label>
                                    <input type="text" class="form-control" id="name" name="name" 
                                        value="<?php echo htmlspecialchars($user['nome']); ?>" required="required" maxlength="32" />
                                </div>
                                <div class="col-sm-6">
                                    <label for="surname" class="form-label fw-semibold">Cognome</label>
                                    <input type="text" class="form-control" id="surname" name="surname" 
                                        value="<?php echo htmlspecialchars($user['cognome']); ?>" required="required" maxlength="32" />
                                </div>
                            </div>

                            <div class="mb-3">
                                <label for="email" class="form-label fw-semibold">E-mail</label>
                                <input type="email" class="form-control" id="email" name="email" 
                                    value="<?php echo htmlspecialchars($user['email']); ?>" required="required" maxlength="255" />
                            </div>

                            <div class="mb-4">
                                <label for="bio" class="form-label fw-semibold">Descrizione</label>
                                <textarea class="form-control" id="bio" name="bio" rows="5" 
                                    maxlength="200" placeholder="Biografía dell'utente..."><?php echo htmlspecialchars($user['descrizione']); ?></textarea>
                            </div>

                            <div class="d-flex justify-content-end mt-4">
                                <button type="submit" class="btn btn-warning fw-bold px-5 text-dark" id="save-user-btn">
                                    <em class="bi bi-save me-2"></em>Salva Modifiche
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
