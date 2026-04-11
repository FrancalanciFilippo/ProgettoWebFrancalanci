        <!-- Bottone Filtri -->
        <div class="container mt-3">
            <button class="btn btn-sm btn-outline-secondary" type="button"
                data-bs-toggle="offcanvas" data-bs-target="#filterOffcanvas">
                <em class="bi bi-funnel me-2"></em>Filtra i Post
            </button>
        </div>
    <div class="container">

    


    <!-- Offcanvas mobile -->
    <div class="offcanvas offcanvas-start" tabindex="-1" id="filterOffcanvas" role="dialog">
        <div class="offcanvas-header border-bottom">
            <h2 class="offcanvas-title fs-6 fw-bold" id="filterOffcanvasLabel">Filtri di Ricerca</h2>
            <button type="button" class="btn-close" data-bs-dismiss="offcanvas"
                aria-label="Chiudi filtri"></button>
        </div>
        <div class="offcanvas-body">
            <form action="#" method="get" id="filter-form-mobile">
                <div class="mb-3">
                    <label for="sort-mobile" class="form-label small">Ordina per</label>
                    <select class="form-select form-select-sm" id="sort-mobile" name="sort">
                        <option value="recenti" selected="selected">Pi&ugrave; recenti</option>
                        <option value="meno-recenti">Meno recenti</option>
                    </select>
                </div>

                <div class="mb-3">
                    <label for="subject-mobile" class="form-label small">Materia</label>
                    <select class="form-select form-select-sm" id="subject-mobile" name="subject">
                        <option value="">Tutte le materie</option>
                        <!-- Le opzioni verranno caricate dinamicamente via JavaScript -->
                    </select>
                </div>

                <div class="mb-3">
                    <label for="type-mobile" class="form-label small">Tipo</label>
                    <select class="form-select form-select-sm" id="type-mobile" name="type">
                        <option value="">Tutti i tipi</option>
                        <option value="sessione">Sessione di studio</option>
                        <option value="progettuale">Progetto di gruppo</option>
                    </select>
                </div>

                <div class="mb-3">
                    <label for="date-mobile" class="form-label small fw-semibold">Data inizio (da)</label>
                    <input type="date" class="form-control form-control-sm" id="date-mobile" name="date_from" />
                </div>


                <div class="mb-4 form-check form-switch">
                    <input class="form-check-input" type="checkbox" role="switch" id="show-unavailable-mobile" name="show_unavailable" />
                    <label class="form-check-label small" for="show-unavailable-mobile">Mostra post non disponibili</label>
                    <div class="form-text mt-1 form-text-sm">Include post scaduti o con posti esauriti.</div>
                </div>

                <button type="submit" class="btn btn-custom-primary btn-sm w-100" id="apply-filters-mobile">
                    <em aria-hidden="true"></em>Filtra
                </button>
            </form>

            <div class="mt-3">
                <button type="button" class="btn btn-outline-danger btn-sm w-100" id="reset-filters-mobile">
                    <em aria-hidden="true"></em>Reset Filtri
                </button>
            </div>
        </div>
    </div>


    <!-- Feed dei Post -->
    <div class="container py-4 flex-grow-1">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2 class="h2 fw-bold">Gruppi e Sessioni</h2>
            <span class="badge bg-secondary" id="posts-count-badge">0 Trovati</span>
        </div>

        <div id="posts-container"></div>
    </div>

    </div>
