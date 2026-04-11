<div class="container py-5">
        
        <div class="row justify-content-center">
            <div class="col-12">
        <div class="mb-4">
            <h1 class="h1 fw-bold mb-1">Crea un nuovo post</h1>
            <p class="text-secondary">Vuoi organizzare un ritrovo in biblioteca per studiare in compagnia o ti manca un membro per il progetto assegnato dal professore? Compila il form qui sotto!</p>
        </div>

        <div class="card">
            <div class="card-body p-4">
                <form action="#" method="post" id="create-post-form">
                    
                    <fieldset class="mb-4 border-0 p-0 m-0">
                        <legend class="fw-semibold mb-2 text-dark fs-6">Che cosa vuoi organizzare?</legend>
                        
                        <div class="row g-3">
                            <div class="col-sm-6">
                                <input type="radio" class="btn-check" name="post_type" id="type_session" value="session" checked="checked" />
                                <label class="btn btn-post-type w-100 py-3 text-start d-flex align-items-center gap-3 rounded-3" for="type_session">
                                    <em class="bi bi-book fs-3 icon-post-type" aria-hidden="true"></em>
                                    <span>
                                        <span class="fw-bold title-post-type d-block">Sessione di studio</span>
                                        <small class="desc-post-type">Incontriamoci e studiamo insieme.</small>
                                    </span>
                                </label>
                            </div>
                            <div class="col-sm-6">
                                <input type="radio" class="btn-check" name="post_type" id="type_project" value="project" />
                                <label class="btn btn-post-type w-100 py-3 text-start d-flex align-items-center gap-3 rounded-3" for="type_project">
                                    <em class="bi bi-diagram-3 fs-3 icon-post-type" aria-hidden="true"></em>
                                    <span>
                                        <span class="fw-bold title-post-type d-block">Progetto di gruppo</span>
                                        <small class="desc-post-type">Creiamo un team per un esame.</small>
                                    </span>
                                </label>
                            </div>
                        </div>
                    </fieldset>

                    <hr class="my-4 text-secondary opacity-25" />

                    <!-- Sezione Informazioni Base -->
                    <h2 class="h5 fw-bold mb-3">Informazioni Generali</h2>
                    
                    <div class="mb-3">
                        <label for="titoloPost" class="form-label fw-medium">Titolo del Post <span class="text-danger">*</span></label>
                        <input type="text" class="form-control focus-ring" id="titoloPost" name="titolo" placeholder="Es. Preparazione esame Reti di Calcolatori" required="required" />
                    </div>

                    <div class="row g-3 mb-3">
                        <div class="col-md-8">
                            <label for="materiaSelezionata" class="form-label fw-medium">
                                Materia <span class="text-danger">*</span>
                            </label>
                            <select class="form-select focus-ring" id="materiaSelezionata" name="materia" required="required">
                                <option value="" selected="selected">Caricamento materie...</option>
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label for="maxPartecipanti" class="form-label fw-medium">Partecipanti <span class="text-danger">*</span></label>
                            <input type="number" class="form-control focus-ring" id="maxPartecipanti" name="partecipanti_max" min="2" max="20" placeholder="Max" required="required" />
                        </div>
                    </div>

                    <div class="mb-4">
                        <label for="luogoIncontro" class="form-label fw-medium">Luogo dell'incontro <span class="text-danger">*</span></label>
                        <input type="text" class="form-control focus-ring" id="luogoIncontro" name="luogo" placeholder="Es. Biblioteca Campus oppure Online (Link Teams/Discord)" required="required" />
                    </div>

                    <hr class="my-4 text-secondary opacity-25" />

                    <!-- Sezione Date -->
                    <h2 class="h5 fw-bold mb-3">Pianificazione</h2>
                    
                    <div class="row g-3 mb-4">
                        <div class="col-sm-6">
                            <label for="dataInizio" class="form-label fw-medium">Da (Inizio) <span class="text-danger">*</span></label>
                            <input type="date" class="form-control focus-ring" id="dataInizio" name="data_inizio" required="required" />
                        </div>
                        <div class="col-sm-6">
                            <label for="dataFine" class="form-label fw-medium">A (Fine) <span class="text-danger">*</span></label>
                            <input type="date" class="form-control focus-ring" id="dataFine" name="data_fine" required="required" />
                        </div>
                    </div>


                    <hr class="my-4 text-secondary opacity-25" />

                    <!-- Descrizione e Pubblicazione -->
                    <h2 class="h5 fw-bold mb-3">Dettagli</h2>
                    
                    <div class="mb-4">
                        <label for="descrizionePost" class="form-label fw-medium">Descrizione e materiale aggiuntivo</label>
                        <textarea class="form-control focus-ring" id="descrizionePost" name="descrizione" rows="4" placeholder="Fornisci maggiori dettagli ai futuri partecipanti (es. argomenti trattati, requisiti)..."></textarea>
                    </div>

                    <!-- Bottoni Invio -->
                    <div class="d-flex flex-column flex-sm-row justify-content-end gap-2 mt-5">
                        <button type="submit" id="create-post-submit" class="btn btn-custom-primary px-5 fw-bold w-100 w-sm-auto">
                            <em class="bi bi-send me-2" aria-hidden="true"></em>Pubblica il Post
                        </button>
                    </div>

                </form>
            </div>
        </div>
            </div>
        </div>
    
    </div>
