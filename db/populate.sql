-- Popolamento completo del database di SchoolTogether
-- Disabilita i vincoli di foreign key
SET FOREIGN_KEY_CHECKS = 0;

-- Svuota tutte le tabelle
TRUNCATE TABLE Commento;
TRUNCATE TABLE Partecipazione;
TRUNCATE TABLE File;
TRUNCATE TABLE Post;
TRUNCATE TABLE Utente;
TRUNCATE TABLE Materia;

-- Abilita i constraint
SET FOREIGN_KEY_CHECKS = 1;

-- ═══════════════════════════════════════════════════════════
-- MATERIE
-- ═══════════════════════════════════════════════════════════
INSERT INTO Materia (nome) VALUES 
('Informatica'),
('Matematica'),
('Fisica'),
('Economia'),
('Biologia'),
('Chimica'),
('Lingue'),
('Storia'),
('Letteratura'),
('Altro...');

-- ═══════════════════════════════════════════════════════════
-- UTENTI
-- ═══════════════════════════════════════════════════════════
INSERT INTO Utente (email, nome, cognome, password, descrizione, tipo) VALUES
-- ADMIN
('admin@admin.com', 'Admin', 'Sistema', 'admin', 'Account amministratore della piattaforma SchoolTogether.', 'admin'),

-- UTENTI NORMALI
('mario.rossi@studenti.it', 'Mario', 'Rossi', '1234', 'Studente di Informatica, appassionato di sviluppo web e programmazione.', 'utente'),
('giulia.ferrari@studenti.it', 'Giulia', 'Ferrari', '1234', 'Studentessa di Matematica, amo l''analisi e la statistica.', 'utente'),
('luca.bianchi@studenti.it', 'Luca', 'Bianchi', '1234', 'Studente di Fisica, appassionato di meccanica quantistica.', 'utente'),
('anna.verdi@studenti.it', 'Anna', 'Verdi', '1234', 'Economia aziendale, cerco collaborazioni per i progetti universitari.', 'utente'),
('marco.esposito@studenti.it', 'Marco', 'Esposito', '1234', 'Studente multidisciplinare, interessi vari.', 'utente'),
('sara.ricci@studenti.it', 'Sara', 'Ricci', '1234', 'Terzo anno di Biologia, mi piace studiare in gruppo.', 'utente'),
('davide.colombo@studenti.it', 'Davide', 'Colombo', '1234', 'Studente di Chimica, specializzato in chimica organica.', 'utente'),
('francesca.russo@studenti.it', 'Francesca', 'Russo', '1234', 'Informatica, esperienza in web development.', 'utente'),
('matteo.gallo@studenti.it', 'Matteo', 'Gallo', '1234', 'Studente di Economia, interessato a startup e innovazione.', 'utente'),
('elena.conti@studenti.it', 'Elena', 'Conti', '1234', 'Lettere e Letteratura, insegnamento e ricerca documentale.', 'utente');

-- ═══════════════════════════════════════════════════════════
-- POST
-- ═══════════════════════════════════════════════════════════
INSERT INTO Post (titolo, tipo, descrizione, data_inizio, data_fine, luogo, max_partecipanti, utente_email, materia_id) VALUES

-- Sessioni dirette (no approvazione)
('Preparazione Analisi 1', 'sessione', 'Sessione di studio collettiva per prepararsi all''esame di Analisi 1. Portiamo il Bramanti e gli appunti delle lezioni.', '2026-04-12 14:00:00', '2026-04-20 18:00:00', 'Aula Studio Campus – Edificio B', 10, 'mario.rossi@studenti.it', 2),

('Ripasso Meccanica Classica', 'sessione', 'Ripasso degli argomenti di Meccanica per il parziale: cinematica, dinamica e lavoro-energia.', '2026-04-08 10:00:00', '2026-04-10 13:00:00', 'Biblioteca Centrale – Sala Silenziosa', 6, 'luca.bianchi@studenti.it', 3),

('Laboratorio Chimica Organica', 'sessione', 'Studio condiviso delle reazioni di chimica organica in preparazione al laboratorio. Portiamo dispense e schemi di reazione.', '2026-04-15 15:00:00', '2026-04-15 18:00:00', 'Aula 3C – Edificio Chimica', 8, 'davide.colombo@studenti.it', 6),

('Algoritmi e Strutture Dati – Esercitazione', 'sessione', 'Esercitazione pratica su grafi, alberi e algoritmi di ordinamento. Utile per il laboratorio di ASD.', '2026-04-20 16:00:00', '2026-04-22 19:00:00', 'Laboratorio Informatica – Piano 2', 12, 'mario.rossi@studenti.it', 1),

('Esercitazione Microeconomia', 'sessione', 'Risolviamo insieme gli esercizi di microeconomia in preparazione all''esame. Portiamo i testi consigliati.', '2026-04-10 09:00:00', '2026-04-12 11:00:00', 'Aula Economia – Blocco A', 7, 'anna.verdi@studenti.it', 4),

('Discussione Biologia Marina', 'sessione', 'Incontro per discutere di biologia marina e ecosistemi acquatici. Condivisione articoli e ricerche.', '2026-04-18 15:30:00', '2026-04-18 17:30:00', 'Laboratorio Biologia – Piano 3', 5, 'sara.ricci@studenti.it', 5),

('Programmazione in JavaScript', 'sessione', 'Lezione pratica su JavaScript moderno, ES6+, async/await. Aperto a tutti i livelli.', '2026-04-14 16:00:00', '2026-04-16 18:00:00', 'Aula Informatica – Lab 1', 15, 'francesca.russo@studenti.it', 1),

-- Progetti con approvazione
('Sviluppo Web App Gestionale', 'progettuale', 'Progetto di gruppo per sviluppare una web app gestionale in PHP e MySQL. Cerco collaboratori motivati con conoscenze base di HTML/CSS.', '2026-11-05 09:00:00', '2027-01-15 18:00:00', 'Discord / Online', 4, 'giulia.ferrari@studenti.it', 1),

('Business Plan Startup', 'progettuale', 'Costruzione di un business plan completo per una startup fittizia. Verranno usati strumenti come Canvas e analisi SWOT.', '2026-05-01 09:00:00', '2026-06-30 18:00:00', 'Aula Riunioni – Facoltà di Economia', 5, 'matteo.gallo@studenti.it', 4),

('Ricerca Biologica su Ecosistemi Acquatici', 'progettuale', 'Progetto di ricerca che analizza la biodiversità di un ecosistema acquatico locale. Richiede uscite sul campo.', '2026-05-10 08:00:00', '2026-07-31 18:00:00', 'Campus + uscite esterne', 3, 'sara.ricci@studenti.it', 5),

('Analisi Testi Letterari Medievali', 'progettuale', 'Progetto di ricerca su testi letterari della letteratura medievale. Analisi stilistica e storica.', '2026-04-25 10:00:00', '2026-06-15 18:00:00', 'Biblioteca Universitaria', 4, 'elena.conti@studenti.it', 9);

-- ═══════════════════════════════════════════════════════════
-- PARTECIPAZIONI (iscrizioni dirette ai post senza approvazione)
-- ═══════════════════════════════════════════════════════════
INSERT INTO Partecipazione (utente_email, post_id) VALUES
-- Post 1 – Analisi 1
('giulia.ferrari@studenti.it', 1),
('luca.bianchi@studenti.it', 1),
('sara.ricci@studenti.it', 1),
('davide.colombo@studenti.it', 1),

-- Post 2 – Meccanica
('mario.rossi@studenti.it', 2),
('anna.verdi@studenti.it', 2),
('sara.ricci@studenti.it', 2),
('francesca.russo@studenti.it', 2),

-- Post 3 – Chimica
('giulia.ferrari@studenti.it', 3),
('anna.verdi@studenti.it', 3),
('matteo.gallo@studenti.it', 3),

-- Post 4 – ASD
('giulia.ferrari@studenti.it', 4),
('luca.bianchi@studenti.it', 4),
('francesca.russo@studenti.it', 4),
('marco.esposito@studenti.it', 4),
('matteo.gallo@studenti.it', 4),

-- Post 5 – Microeconomia
('mario.rossi@studenti.it', 5),
('luca.bianchi@studenti.it', 5),
('davide.colombo@studenti.it', 5),

-- Post 6 – Biologia Marina
('luca.bianchi@studenti.it', 6),
('davide.colombo@studenti.it', 6),

-- Post 7 – JavaScript
('mario.rossi@studenti.it', 7),
('luca.bianchi@studenti.it', 7),
('anna.verdi@studenti.it', 7),
('matteo.gallo@studenti.it', 7),
('elena.conti@studenti.it', 7);


-- ═══════════════════════════════════════════════════════════
-- COMMENTI (incluse risposte annidate)
-- ═══════════════════════════════════════════════════════════
INSERT INTO Commento (testo, risposta_id, utente_email, post_id) VALUES
-- Post 1 – Analisi 1
('Qualcuno sa dove trovare il PDF del Bramanti aggiornato? Non riesco a scaricarlo dalla libreria.', NULL, 'luca.bianchi@studenti.it', 1),
('Ho caricato il PDF nella sezione materiali del post! Sono gli appunti integralissimi.', 1, 'mario.rossi@studenti.it', 1),
('Ci vediamo alle 14:00 come concordato, giusto? Portiamo anche i quaderni.', NULL, 'giulia.ferrari@studenti.it', 1),
('Sì, confermato! Porto anche gli esercizi del Giusti e il formulario.', 3, 'mario.rossi@studenti.it', 1),
('Domanda: possiamo usare la calcolatrice durante la sessione?', NULL, 'davide.colombo@studenti.it', 1),

-- Post 2 – Meccanica
('Che dire, è un argomento complesso. Iniziamo dalla cinematica base?', NULL, 'anna.verdi@studenti.it', 2),
('Sì, buona idea. Poi passiamo a dinamica e infine energia.', 6, 'luca.bianchi@studenti.it', 2),

-- Post 4 – ASD
('Qualcuno ha già preparato l''algoritmo di Dijkstra? Possiamo fare una board condivisa.', NULL, 'giulia.ferrari@studenti.it', 4),
('Ho una implementazione in Java se volete partire da quella come base.', 9, 'mario.rossi@studenti.it', 4),
('Io preferisco C++, è più veloce. Comunque possiamo separare in gruppi.', NULL, 'francesca.russo@studenti.it', 4),

-- Post 5 – Microeconomia
('Ragazzi, avete il libro di testo? Penso che sia necessario almeno per gli esercizi.', NULL, 'mario.rossi@studenti.it', 5),
('Sì, io l''ho preso. Se ne avete bisogno posso fare fotocopie delle parti principali.', 12, 'luca.bianchi@studenti.it', 5),

-- Post 7 – JavaScript
('Non vedo l''ora! Non riesco con le promise... speriamo di capire meglio.', NULL, 'anna.verdi@studenti.it', 7),
('Le promise sono il fondamento di async/await. Con pratica è facile!', 14, 'francesca.russo@studenti.it', 7),

-- Post 8 – Web App
('Penso che PHP vanilla + Bootstrap sia un buon start. Che ne dite?', NULL, 'mario.rossi@studenti.it', 8),
('D''accordo, ma in futuro potremmo migrare a Laravel se il progetto cresce.', 16, 'luca.bianchi@studenti.it', 8),

-- Post 10 – Ricerca Biologica
('Sarà necessario fare escursioni sul campo? Quando potremmo farle?', NULL, 'giulia.ferrari@studenti.it', 10),
('Sì, sono essenziali per raccogliere dati. Pensavo di farle in maggio quando il clima è migliore.', 18, 'sara.ricci@studenti.it', 10);
