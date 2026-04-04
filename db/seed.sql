
-- Password: "Password123!" hashata con bcrypt (per test)
INSERT INTO Utente (email, nome, cognome, password, descrizione, tipo) VALUES
(
    'mario.rossi@studenti.it', 'Mario', 'Rossi',
    '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi',
    'Studente di Informatica, appassionato di sviluppo web.',
    'utente'
),
(
    'giulia.ferrari@studenti.it', 'Giulia', 'Ferrari',
    '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi',
    'Studentessa di Matematica, amo l''analisi e la statistica.',
    'utente'
),
(
    'luca.bianchi@studenti.it', 'Luca', 'Bianchi',
    '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi',
    'Frequento il secondo anno di Fisica.',
    'utente'
),
(
    'anna.verdi@studenti.it', 'Anna', 'Verdi',
    '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi',
    'Economia aziendale, cerco collaborazioni per i progetti.',
    'utente'
),
(
    'marco.esposito@studenti.it', 'Marco', 'Esposito',
    '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi',
    NULL,
    'utente'
),
(
    'sara.ricci@studenti.it', 'Sara', 'Ricci',
    '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi',
    'Terzo anno, mi piace studiare in gruppo.',
    'utente'
),
(
    'admin@schooltogether.it', 'Admin', 'Sistema',
    '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi',
    'Account amministratore della piattaforma.',
    'admin'
);


-- ── Post ────────────────────────────────────────────────────
-- tipo: 'sessione' (accesso diretto) | 'progettuale' (richiede consenso)
-- NOTA: richiede_approvazione è separato dal tipo:
--       un post 'sessione' può comunque richiedere approvazione e viceversa.
-- Le materie ora sono filtrate per nome nel frontend
INSERT INTO Post
    (titolo, richiede_approvazione, tipo, descrizione,
     data_inizio, data_fine, luogo,
     max_partecipanti, utente_email, materia_id)
VALUES
(
    'Preparazione Analisi 1',
    0,                       -- accesso diretto
    'sessione',
    'Sessione di studio collettiva per prepararsi all''esame di Analisi 1. Portiamo il Bramanti e gli appunti delle lezioni.',
    '2026-04-12 14:00:00', '2026-04-20 18:00:00',
    'Aula Studio Campus – Edificio B',
    10, 'mario.rossi@studenti.it', 2   -- Matematica
),
(
    'Sviluppo Web App Gestionale',
    1,                       -- richiede approvazione
    'progettuale',
    'Progetto di gruppo per sviluppare una web app gestionale in PHP e MySQL. Cerco collaboratori motivati con conoscenze base di HTML/CSS.',
    '2026-11-05 09:00:00', '2027-01-15 18:00:00',
    'Discord / Online',
    4, 'giulia.ferrari@studenti.it', 1  -- Informatica
),
(
    'Ripasso Meccanica Classica',
    0,
    'sessione',
    'Ripasso degli argomenti di Meccanica per il parziale: cinematica, dinamica e lavoro-energia.',
    '2026-04-08 10:00:00', '2026-04-10 13:00:00',
    'Biblioteca Centrale – Sala Silenziosa',
    6, 'luca.bianchi@studenti.it', 3    -- Fisica
),
(
    'Business Plan Startup',
    1,
    'progettuale',
    'Costruzione di un business plan completo per una startup fittizia. Verranno usati strumenti come Canvas e analisi SWOT.',
    '2026-05-01 09:00:00', '2026-06-30 18:00:00',
    'Aula Riunioni – Facoltà di Economia',
    5, 'anna.verdi@studenti.it', 4      -- Economia
),
(
    'Laboratorio Chimica Organica',
    0,
    'sessione',
    'Studio condiviso delle reazioni di chimica organica in preparazione al laboratorio. Portiamo dispense e schemi di reazione.',
    '2026-04-15 15:00:00', '2026-04-15 18:00:00',
    'Aula 3C – Edificio Chimica',
    8, 'marco.esposito@studenti.it', 6  -- Chimica
),
(
    'Algoritmi e Strutture Dati – Esercitazione',
    0,
    'sessione',
    'Esercitazione pratica su grafi, alberi e algoritmi di ordinamento. Utile per il laboratorio di ASD.',
    '2026-04-20 16:00:00', '2026-04-22 19:00:00',
    'Laboratorio Informatica – Piano 2',
    12, 'mario.rossi@studenti.it', 1    -- Informatica
),
(
    'Ricerca Biologica su Ecosistemi Acquatici',
    1,
    'progettuale',
    'Progetto di ricerca che analizza la biodiversità di un ecosistema acquatico locale. Richiede uscite sul campo.',
    '2026-05-10 08:00:00', '2026-07-31 18:00:00',
    'Campus + uscite esterne',
    3, 'sara.ricci@studenti.it', 5      -- Biologia
);


-- ── Partecipazioni (post senza approvazione o richieste accettate) ──────────
INSERT INTO Partecipazione (utente_email, post_id) VALUES
-- Post 1 – Analisi 1 (sessione diretta)
('giulia.ferrari@studenti.it', 1),
('luca.bianchi@studenti.it',   1),
('sara.ricci@studenti.it',     1),

-- Post 2 – Web App (progettuale con approvazione: luca accettato)
('luca.bianchi@studenti.it',   2),

-- Post 3 – Meccanica (sessione diretta)
('mario.rossi@studenti.it',    3),
('anna.verdi@studenti.it',     3),
('sara.ricci@studenti.it',     3),

-- Post 5 – Chimica (sessione diretta)
('giulia.ferrari@studenti.it', 5),
('anna.verdi@studenti.it',     5),

-- Post 6 – ASD (sessione diretta)
('giulia.ferrari@studenti.it', 6),
('luca.bianchi@studenti.it',   6),
('anna.verdi@studenti.it',     6),
('marco.esposito@studenti.it', 6);


-- ── Richieste (solo post con richiede_approvazione = 1) ─────────────────────
INSERT INTO Richiesta (utente_email, post_id, stato_id) VALUES
-- Post 2 – Web App Gestionale
('mario.rossi@studenti.it',    2, 1),  -- In attesa
('luca.bianchi@studenti.it',   2, 2),  -- Accettata  (→ presente in Partecipazione)
('marco.esposito@studenti.it', 2, 3),  -- Rifiutata
('sara.ricci@studenti.it',     2, 1),  -- In attesa

-- Post 4 – Business Plan
('mario.rossi@studenti.it',    4, 1),  -- In attesa
('marco.esposito@studenti.it', 4, 3),  -- Rifiutata
('luca.bianchi@studenti.it',   4, 2),  -- Accettata

-- Post 7 – Ricerca Biologica
('giulia.ferrari@studenti.it', 7, 1),  -- In attesa
('anna.verdi@studenti.it',     7, 2);  -- Accettata


-- ── Commenti (incluse risposte annidate) ────────────────────────────────────
INSERT INTO Commento (testo, risposta_id, utente_email, post_id) VALUES
-- Post 1
('Qualcuno sa dove trovare il PDF del Bramanti? Non riesco a scaricarlo.',
 NULL, 'luca.bianchi@studenti.it', 1),

('Ho caricato il PDF nella sezione materiali del post! Controllate lì.',
 1,   'mario.rossi@studenti.it', 1),   -- risponde a commento 1

('Ci vediamo alle 14:00 come concordato, giusto?',
 NULL, 'giulia.ferrari@studenti.it', 1),

('Sì, confermato! Porto anche gli esercizi del Giusti.',
 3,   'mario.rossi@studenti.it', 1),   -- risponde a commento 3

-- Post 2
('Che stack tecnologico pensate di usare? Solo PHP o anche un framework?',
 NULL, 'mario.rossi@studenti.it', 2),

('Pensavo PHP vanilla + Bootstrap per il frontend. Niente framework per ora.',
 5,   'giulia.ferrari@studenti.it', 2),  -- risponde a commento 5

('Ho già esperienza con Laravel, potrei proporre quello se vi va.',
 NULL, 'luca.bianchi@studenti.it', 2),

-- Post 3
('Porto anche gli appunti del prof. Marini sulle forze di attrito, vi servono?',
 NULL, 'anna.verdi@studenti.it', 3),

('Sì grazie Anna! Aggiungerei anche il capitolo sull''energia potenziale.',
 8,   'luca.bianchi@studenti.it', 3),

-- Post 6
('Qualcuno ha già preparato il Dijkstra? Possiamo fare una board condivisa.',
 NULL, 'giulia.ferrari@studenti.it', 6),

('Ho una implementazione in Java se volete partire da quella.',
 10,  'mario.rossi@studenti.it', 6);