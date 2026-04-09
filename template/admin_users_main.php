<section class="py-5 blur-entrance">
    <div class="container">
        <div class="mb-4">
            <h1 class="h2 fw-bold text-dark mb-1">Gestione Utenti</h1>
            <p class="text-secondary small mb-0">Visualizzazione di tutti gli iscritti al portale.</p>
        </div>

        <div class="card border-0 shadow-sm">
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="bg-light">
                            <tr>
                                <th class="ps-4 py-3 text-secondary small text-uppercase fw-bold">Utente</th>
                                <th class="py-3 text-secondary small text-uppercase fw-bold">Email</th>
                                <th class="pe-4 py-3 text-secondary small text-uppercase fw-bold text-end">Azioni</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (empty($templateParams["utenti"])): ?>
                                <tr>
                                    <td colspan="3" class="text-center py-5 text-muted">
                                        Nessun utente registrato al momento.
                                    </td>
                                </tr>
                            <?php else: ?>
                                <?php foreach($templateParams["utenti"] as $user): ?>
                                    <tr id="user-row-<?php echo $user['id']; ?>">
                                        <td class="ps-4 py-3">
                                            <div class="d-flex align-items-center">
                                                <div class="admin-user-avatar text-white me-3 d-flex align-items-center justify-content-center fw-bold rounded-circle shadow-sm flex-shrink-0" 
                                                     style="width: 40px; height: 40px;" 
                                                     data-user="<?php echo htmlspecialchars($user['nome'] . ' ' . $user['cognome']); ?>"></div>
                                                <div>
                                                    <div class="fw-bold text-dark"><?php echo htmlspecialchars($user['nome'] . ' ' . $user['cognome']); ?></div>
                                                    <div class="text-muted small">ID: #<?php echo $user['id']; ?></div>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="py-3">
                                            <span class="text-secondary"><?php echo htmlspecialchars($user['email']); ?></span>
                                        </td>
                                        <td class="pe-4 py-3 text-end">
                                            <div class="d-flex justify-content-end gap-2 text-nowrap">
                                                <a href="admin_edit_user.php?id=<?php echo $user['id']; ?>" class="btn btn-warning btn-sm fw-semibold text-dark">
                                                    <em class="bi bi-pencil me-1"></em>Modifica
                                                </a>
                                                <button type="button" class="btn btn-danger btn-sm fw-semibold" onclick="deleteUser(<?php echo $user['id']; ?>, '<?php echo htmlspecialchars($user['email']); ?>')">
                                                    <em class="bi bi-trash me-1"></em>Elimina
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</section>
