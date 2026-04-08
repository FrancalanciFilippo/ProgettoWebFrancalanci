<div class="mb-4 d-flex justify-content-between align-items-end">
    <div>
        <h1 class="h2 fw-bold mb-1">Post a cui partecipi</h1>
        <p class="text-secondary mb-0">Le sessioni di studio e i progetti a cui sei iscritto.</p>
    </div>
</div>

<!-- Lista Post (popolata dinamicamente via JS) -->
<div id="joined-posts-container" class="d-flex flex-column gap-4">
    <p class="text-muted text-center">Caricamento...</p>
</div>

<!-- Messaggio se vuoto -->
<div id="joined-posts-empty" class="text-center p-5 bg-white rounded-4 border shadow-sm mt-3 d-none">
    <em class="bi bi-people display-4 text-muted mb-3" aria-hidden="true"></em>
    <h2 class="h5 fw-bold">Non partecipi ancora a nessun post</h2>
    <p class="text-secondary">Esplora i post disponibili e unisciti a una sessione!</p>
    <a href="posts.php" class="btn btn-custom-primary mt-2">Esplora Post</a>
</div>
