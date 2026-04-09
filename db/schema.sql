-- Creazione Database
SET FOREIGN_KEY_CHECKS = 0;

DROP TABLE IF EXISTS Commento;
DROP TABLE IF EXISTS Richiesta;
DROP TABLE IF EXISTS Partecipazione;
DROP TABLE IF EXISTS File;
DROP TABLE IF EXISTS Post;
DROP TABLE IF EXISTS Utente;
DROP TABLE IF EXISTS Stato;
DROP TABLE IF EXISTS Materia;

SET FOREIGN_KEY_CHECKS = 1;
CREATE TABLE Materia (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nome VARCHAR(100) NOT NULL
) ENGINE=InnoDB;


CREATE TABLE Utente (
    id INT AUTO_INCREMENT PRIMARY KEY,
    email VARCHAR(255) NOT NULL UNIQUE,
    nome VARCHAR(100) NOT NULL,
    cognome VARCHAR(100) NOT NULL,
    password VARCHAR(255) NOT NULL,
    descrizione TEXT,
    tipo ENUM('admin', 'utente') NOT NULL DEFAULT 'utente'
) ENGINE=InnoDB;

CREATE TABLE Post (
    id INT AUTO_INCREMENT PRIMARY KEY,
    titolo VARCHAR(255) NOT NULL,
    tipo ENUM('sessione', 'progettuale') NOT NULL,
    descrizione TEXT NOT NULL,
    data_inizio DATETIME,
    data_fine DATETIME,
    luogo VARCHAR(255),
    data_creazione TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    max_partecipanti INT NOT NULL DEFAULT 10,
    utente_id INT NOT NULL,
    materia_id INT NOT NULL,
    FOREIGN KEY (utente_id) REFERENCES Utente(id) ON DELETE CASCADE,
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
    utente_id INT NOT NULL,
    post_id INT NOT NULL,
    PRIMARY KEY (utente_id, post_id),
    FOREIGN KEY (utente_id) REFERENCES Utente(id) ON DELETE CASCADE,
    FOREIGN KEY (post_id) REFERENCES Post(id) ON DELETE CASCADE
) ENGINE=InnoDB;


CREATE TABLE Commento (
    id INT AUTO_INCREMENT PRIMARY KEY,
    testo TEXT NOT NULL,
    data_scrittura TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    risposta_id INT,
    utente_id INT NOT NULL,
    post_id INT NOT NULL,
    FOREIGN KEY (risposta_id) REFERENCES Commento(id) ON DELETE SET NULL,
    FOREIGN KEY (utente_id) REFERENCES Utente(id) ON DELETE CASCADE,
    FOREIGN KEY (post_id) REFERENCES Post(id) ON DELETE CASCADE
) ENGINE=InnoDB;

INSERT INTO Materia (nome) VALUES ('Informatica'), ('Matematica'), ('Fisica'), ('Economia'), ('Biologia'), ('Chimica'), ('Altro...');
