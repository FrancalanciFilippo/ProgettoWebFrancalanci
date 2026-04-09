



-- ── Utenti di Test (password in chiaro) ────────────────────────────────
INSERT IGNORE INTO Utente (id, email, nome, cognome, password, descrizione, tipo) VALUES
(1, 'utente@studenti.it', 'Studente', 'Normale', 'password123', 'Account studente generico per test.', 'utente'),
(2, 'admin@schooltogether.it', 'Admin', 'Sistema', 'admin123', 'Account amministratore della piattaforma.', 'admin');

-- ── Post ────────────────────────────────────────────────────
-- tipo: 'sessione' (accesso diretto) | 'progettuale'
INSERT IGNORE INTO Post (titolo, tipo, descrizione, data_inizio, data_fine, luogo, max_partecipanti, utente_id, materia_id) VALUES
('Preparazione Analisi 1', 'sessione', 'Sessione di studio collettiva per prepararsi all''esame di Analisi 1.', '2026-04-12 14:00:00', '2026-04-20 18:00:00', 'Aula Studio Campus – Edificio B', 10, 1, 2),

('Sviluppo Web App Gestionale', 'progettuale', 'Progetto di gruppo per sviluppare una web app gestionale.', '2026-11-05 09:00:00', '2027-01-15 18:00:00', 'Discord / Online', 4, 1, 1);

-- ── Partecipazioni ──────────────────────────────────────────
-- Per testare i partecipanti attuali, aggiungiamo l'autore stesso (come fa il codice)
INSERT IGNORE INTO Partecipazione (utente_id, post_id) VALUES
(1, 1),
(1, 2);

-- ── Commenti ────────────────────────────────────────────────
INSERT IGNORE INTO Commento (testo, risposta_id, utente_id, post_id) VALUES
('Ricordatevi di portare il materiale didattico!', NULL, 1, 1);
