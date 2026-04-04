<div class="row min-vh-100">

    <!-- Sidebar Desktop (md+) -->
    <aside class="col-md-4 col-lg-3 d-none d-md-flex flex-column border-end bg-body-tertiary p-4">
        <h1 class="h5 fw-bold mb-4">
            <em class="bi bi-funnel me-2" aria-hidden="true"></em>Filtri di Ricerca
        </h1>
        
        <form action="" method="get" id="filter-form-desktop">
            <div class="mb-3">
                <label for="sort-desktop" class="form-label small fw-semibold">Ordina per</label>
                <select class="form-select form-select-sm" id="sort-desktop" name="sort">
                    <option value="recenti" selected="selected">Pi&ugrave; recenti</option>
                    <option value="meno-recenti">Meno recenti</option>
                </select>
            </div>

            <div class="mb-3">
                <label for="subject-desktop" class="form-label small fw-semibold">Materia</label>
                <select class="form-select form-select-sm" id="subject-desktop" name="subject">
                    <option value="">Tutte le materie</option>
                    <!-- Le opzioni verranno caricate dinamicamente via JavaScript -->
                </select>
            </div>

            <div class="mb-3">
                <label for="type-desktop" class="form-label small fw-semibold">Tipo</label>
                <select class="form-select form-select-sm" id="type-desktop" name="type">
                    <option value="">Tutti i tipi</option>
                    <option value="sessione">Sessione di studio</option>
                    <option value="progettuale">Progetto di gruppo</option>
                </select>
            </div>

            <div class="mb-3">
                <label for="date-desktop" class="form-label small fw-semibold">Data inizio (da)</label>
                <input type="date" class="form-control form-control-sm" id="date-desktop" name="date_from" />
            </div>

            <div class="mb-3 form-check">
                <input type="checkbox" class="form-check-input" id="no-auth-desktop" name="no_auth" />
                <label class="form-check-label small" for="no-auth-desktop">Non richiedono consenso</label>
            </div>

            <div class="mb-4 form-check form-switch">
                <input class="form-check-input" type="checkbox" role="switch" id="show-unavailable-desktop" name="show_unavailable" />
                <label class="form-check-label small" for="show-unavailable-desktop">Mostra post non disponibili</label>
                <div class="form-text mt-1" style="font-size:0.75rem;">Include post scaduti o con posti esauriti.</div>
            </div>

            <button type="submit" class="btn btn-custom-primary btn-sm w-100" id="apply-filters-desktop">
                <em class="bi bi-funnel me-2" aria-hidden="true"></em>Filtra
            </button>
        </form>

        <div class="mt-3">
            <button type="button" class="btn btn-outline-danger btn-sm w-100" id="reset-filters-desktop">
                <em class="bi bi-x-circle me-2" aria-hidden="true"></em>Reset Filtri
            </button>
        </div>
    </aside>

    <!-- Trigger offcanvas su mobile -->
    <div class="col-12 d-md-none border-bottom p-2 bg-light">
        <button class="btn btn-sm btn-outline-secondary w-100 d-flex justify-content-center align-items-center" type="button"
            data-bs-toggle="offcanvas" data-bs-target="#filterOffcanvas"
            aria-controls="filterOffcanvas">
            <em class="bi bi-funnel me-2" aria-hidden="true"></em>
            Filtra i Post
        </button>
    </div>

    <!-- Offcanvas mobile -->
    <div class="offcanvas offcanvas-start" tabindex="-1" id="filterOffcanvas" role="dialog"
        aria-labelledby="filterOffcanvasLabel">
        <div class="offcanvas-header border-bottom">
            <h2 class="offcanvas-title fs-6 fw-bold" id="filterOffcanvasLabel">Filtri di Ricerca</h2>
            <button type="button" class="btn-close" data-bs-dismiss="offcanvas"
                aria-label="Chiudi filtri"></button>
        </div>
        <div class="offcanvas-body">
            <form action="" method="get" id="filter-form-mobile">
                <div class="mb-3">
                    <label for="sort-mobile" class="form-label small fw-semibold">Ordina per</label>
                    <select class="form-select form-select-sm" id="sort-mobile" name="sort">
                        <option value="recenti" selected="selected">Pi&ugrave; recenti</option>
                        <option value="meno-recenti">Meno recenti</option>
                    </select>
                </div>

                <div class="mb-3">
                    <label for="subject-mobile" class="form-label small fw-semibold">Materia</label>
                    <select class="form-select form-select-sm" id="subject-mobile" name="subject">
                        <option value="">Tutte le materie</option>
                        <!-- Le opzioni verranno caricate dinamicamente via JavaScript -->
                    </select>
                </div>

                <div class="mb-3">
                    <label for="type-mobile" class="form-label small fw-semibold">Tipo</label>
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

                <div class="mb-3 form-check">
                    <input type="checkbox" class="form-check-input" id="no-auth-mobile" name="no_auth" />
                    <label class="form-check-label small" for="no-auth-mobile">Non richiedono consenso</label>
                </div>

                <div class="mb-4 form-check form-switch">
                    <input class="form-check-input" type="checkbox" role="switch" id="show-unavailable-mobile" name="show_unavailable" />
                    <label class="form-check-label small" for="show-unavailable-mobile">Mostra post non disponibili</label>
                    <div class="form-text mt-1" style="font-size:0.75rem;">Include post scaduti o con posti esauriti.</div>
                </div>

                <button type="submit" class="btn btn-custom-primary btn-sm w-100" id="apply-filters-mobile">
                    <em class="bi bi-funnel me-2" aria-hidden="true"></em>Filtra
                </button>
            </form>

            <div class="mt-3">
                <button type="button" class="btn btn-outline-danger btn-sm w-100" id="reset-filters-mobile">
                    <em class="bi bi-x-circle me-2" aria-hidden="true"></em>Reset Filtri
                </button>
            </div>
        </div>
    </div>


    <!-- Feed dei Post -->
    <div class="col-12 col-md-8 col-lg-9 p-4">
        
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2 class="h4 fw-bold mb-0">Gruppi e Sessioni</h2>
            <span class="badge text-bg-secondary" id="posts-count-badge">0 Trovati</span>
        </div>

        <div id="posts-container">
            
        </div>

    </div>

</div>
