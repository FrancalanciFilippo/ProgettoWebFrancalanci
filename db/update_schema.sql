-- Aggiornamento database per la nuova struttura
-- Eseguire questo script dopo aver applicato le modifiche allo schema

-- Rinominare la colonna descrizione in nome nella tabella Stato
ALTER TABLE Stato CHANGE descrizione nome VARCHAR(50) NOT NULL;

-- Verifica che tutto sia corretto
SELECT * FROM Stato;
SELECT * FROM Materia;