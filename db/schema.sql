-- Creazione Database
DROP TABLE IF EXISTS Commento;
DROP TABLE IF EXISTS Richiesta;
DROP TABLE IF EXISTS Partecipazione;
DROP TABLE IF EXISTS File;
DROP TABLE IF EXISTS Post;
DROP TABLE IF EXISTS Utente;
DROP TABLE IF EXISTS Stato;
DROP TABLE IF EXISTS Materia;

CREATE TABLE Materia (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nome VARCHAR(100) NOT NULL
) ENGINE=InnoDB;

CREATE TABLE Stato (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nome VARCHAR(50) NOT NULL
) ENGINE=InnoDB;

CREATE TABLE Utente (
    email VARCHAR(255) PRIMARY KEY,
    nome VARCHAR(100) NOT NULL,
    cognome VARCHAR(100) NOT NULL,
    password VARCHAR(255) NOT NULL,
    descrizione TEXT,
    tipo ENUM('admin', 'utente') NOT NULL DEFAULT 'utente'
) ENGINE=InnoDB;

CREATE TABLE Post (
    id INT AUTO_INCREMENT PRIMARY KEY,
    titolo VARCHAR(255) NOT NULL,
    richiede_approvazione BOOLEAN NOT NULL DEFAULT FALSE,
    tipo ENUM('sessione', 'progettuale') NOT NULL,
    descrizione TEXT NOT NULL,
    data_inizio DATETIME,
    data_fine DATETIME,
    luogo VARCHAR(255),
    data_creazione TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    max_partecipanti INT NOT NULL DEFAULT 10,
    utente_email VARCHAR(255) NOT NULL,
    materia_id INT NOT NULL,
    FOREIGN KEY (utente_email) REFERENCES Utente(email) ON UPDATE CASCADE,
    FOREIGN KEY (materia_id) REFERENCES Materia(id) ON DELETE CASCADE
) ENGINE=InnoDB;

CREATE TABLE File (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nome VARCHAR(255) NOT NULL,
    tipo VARCHAR(100),
    dimensione_byte INT NOT NULL,
    contenuto LONGBLOB NOT NULL,
    post_id INT NOT NULL,
    CONSTRAINT chk_file_size CHECK (dimensione_byte <= 5242880),
    FOREIGN KEY (post_id) REFERENCES Post(id) ON DELETE CASCADE
) ENGINE=InnoDB;

CREATE TABLE Partecipazione (
    utente_email VARCHAR(255) NOT NULL,
    post_id INT NOT NULL,
    PRIMARY KEY (utente_email, post_id),
    FOREIGN KEY (utente_email) REFERENCES Utente(email) ON UPDATE CASCADE,
    FOREIGN KEY (post_id) REFERENCES Post(id) ON DELETE CASCADE
) ENGINE=InnoDB;

CREATE TABLE Richiesta (
    id INT AUTO_INCREMENT PRIMARY KEY,
    utente_email VARCHAR(255) NOT NULL,
    post_id INT NOT NULL,
    stato_id INT NOT NULL,
    FOREIGN KEY (utente_email) REFERENCES Utente(email) ON UPDATE CASCADE,
    FOREIGN KEY (post_id) REFERENCES Post(id) ON DELETE CASCADE,
    FOREIGN KEY (stato_id) REFERENCES Stato(id) ON DELETE CASCADE
) ENGINE=InnoDB;

CREATE TABLE Commento (
    id INT AUTO_INCREMENT PRIMARY KEY,
    testo TEXT NOT NULL,
    data_scrittura TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    risposta_id INT,
    utente_email VARCHAR(255) NOT NULL,
    post_id INT NOT NULL,
    FOREIGN KEY (risposta_id) REFERENCES Commento(id) ON DELETE SET NULL,
    FOREIGN KEY (utente_email) REFERENCES Utente(email) ON UPDATE CASCADE,
    FOREIGN KEY (post_id) REFERENCES Post(id) ON DELETE CASCADE
) ENGINE=InnoDB;

INSERT INTO Materia (nome) VALUES ('Informatica'), ('Matematica'), ('Fisica'), ('Economia'), ('Biologia'), ('Chimica'), ('Altro...');

INSERT INTO Stato (nome) VALUES ('In attesa'), ('Accettata'), ('Rifiutata');

INSERT INTO Utente (email, nome, cognome, password, descrizione, tipo) 
VALUES ('deleted@system.it', 'Utente', 'Eliminato', 'SYSTEM_ACCOUNT_SECURE_VAL', 'Questo account gestisce i dati di utenti che hanno eliminato il proprio profilo.', 'admin');

DELIMITER //

CREATE TRIGGER TRG_User_Anonymize
BEFORE DELETE ON Utente
FOR EACH ROW
BEGIN
    IF OLD.email <> 'deleted@system.it' THEN
        UPDATE Post SET utente_email = 'deleted@system.it' WHERE utente_email = OLD.email;
        UPDATE Commento SET utente_email = 'deleted@system.it' WHERE utente_email = OLD.email;
        UPDATE Richiesta SET utente_email = 'deleted@system.it' WHERE utente_email = OLD.email;
        UPDATE Partecipazione SET utente_email = 'deleted@system.it' WHERE utente_email = OLD.email;
    END IF;
END;
//

DELIMITER ;
