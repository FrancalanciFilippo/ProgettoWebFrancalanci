<section class="py-5 blur-entrance">
    <div class="container">
        <div class="mb-4">
            <h1 class="h2 fw-bold text-dark mb-1">Gestione Post</h1>
            <p class="text-secondary small mb-0">Monitoraggio e moderazione di tutti i contenuti pubblicati.</p>
        </div>

        <div class="card border-0 shadow-sm">
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="bg-light">
                            <tr>
                                <th class="ps-4 py-3 text-secondary small text-uppercase fw-bold">Titolo Post</th>
                                <th class="py-3 text-secondary small text-uppercase fw-bold">Autore</th>
                                <th class="pe-4 py-3 text-secondary small text-uppercase fw-bold text-end">Azioni</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (empty($templateParams["posts"])): ?>
                                <tr>
                                    <td colspan="3" class="text-center py-5 text-muted">
                                        Nessun post pubblicato al momento.
                                    </td>
                                </tr>
                            <?php else: ?>
                                <?php foreach($templateParams["posts"] as $post): ?>
                                    <tr id="post-row-<?php echo $post['id']; ?>">
                                        <td class="ps-4 py-3">
                                            <div class="fw-bold text-dark"><?php echo htmlspecialchars($post['titolo']); ?></div>
                                            <div class="text-muted small">ID: #<?php echo $post['id']; ?></div>
                                        </td>
                                        <td class="py-3">
                                            <div class="d-flex align-items-center">
                                                <em class="bi bi-person-circle me-2 text-secondary"></em>
                                                <div>
                                                    <div class="small fw-semibold"><?php echo htmlspecialchars($post['creatore_nome'] . ' ' . $post['creatore_cognome']); ?></div>
                                                    <div class="text-muted extra-small" style="font-size: 0.75rem;"><?php echo htmlspecialchars($post['creatore_email']); ?></div>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="pe-4 py-3 text-end">
                                            <div class="d-flex justify-content-end gap-2 text-nowrap">
                                                <a href="admin_edit_post.php?id=<?php echo $post['id']; ?>" class="btn btn-warning btn-sm fw-semibold text-dark">
                                                    <em class="bi bi-pencil me-1"></em>Modifica
                                                </a>
                                                <button type="button" class="btn btn-danger btn-sm fw-semibold" onclick="deletePost(<?php echo $post['id']; ?>, '<?php echo htmlspecialchars(addslashes($post['titolo'])); ?>')">
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
