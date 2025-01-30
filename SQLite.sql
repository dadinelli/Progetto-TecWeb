DROP TABLE IF EXISTS Prenotazione_Tavoli;
DROP TABLE IF EXISTS Prenotazione;
DROP TABLE IF EXISTS Recensione;
DROP TABLE IF EXISTS Tavoli;
DROP TABLE IF EXISTS Cliente;
DROP TABLE IF EXISTS Cibo;  -- Se non è commentata, rimuovi questa riga
DROP TABLE IF EXISTS Owner;

CREATE TABLE Cliente (
    ID_Cliente INT AUTO_INCREMENT PRIMARY KEY,
    Nome VARCHAR(100) NOT NULL,
    Cognome VARCHAR(100) NOT NULL,
    Email VARCHAR(150) UNIQUE NOT NULL,
    Telefono VARCHAR(15) UNIQUE,
    Username VARCHAR(100) UNIQUE,
    Ruolo VARCHAR(100) DEFAULT "Cliente",
    Pass VARCHAR(100) NOT NULL
);

-- Tabella Prenotazione
CREATE TABLE Prenotazione (
    ID_Prenotazione INT AUTO_INCREMENT PRIMARY KEY,
    Data DATE NOT NULL,
    Ora TIME NOT NULL,
    Numero_Persone INT NOT NULL,
    Stato VARCHAR(50) NOT NULL,
    ID_Cliente INT NOT NULL,
    FOREIGN KEY (ID_Cliente) REFERENCES Cliente(ID_Cliente) ON DELETE CASCADE ON UPDATE CASCADE
);

-- Tabella Recensione
CREATE TABLE Recensione (
    ID_Recensione INT AUTO_INCREMENT PRIMARY KEY,
    Valutazione INT CHECK (Valutazione BETWEEN 1 AND 5),
    Testo TEXT,
    Data DATE NOT NULL,
    ID_Cliente INT NOT NULL,
    FOREIGN KEY (ID_Cliente) REFERENCES Cliente(ID_Cliente) ON DELETE CASCADE ON UPDATE CASCADE
);

CREATE TABLE Tavoli (
    ID_Tavolo INT(11) AUTO_INCREMENT PRIMARY KEY, -- Identificativo univoco del tavolo
    Numero_Tavolo INT(11) NOT NULL,               -- Numero del tavolo
    Capacita INT(11) NOT NULL                     -- Numero massimo di persone che possono sedersi
);

CREATE TABLE Prenotazione_Tavoli (
    ID_Prenotazione INT(11) NOT NULL, -- Riferimento alla tabella Prenotazione
    ID_Tavolo INT(11) NOT NULL,       -- Riferimento alla tabella Tavoli
    Ora_Inizio TIME NOT NULL,         -- Orario di inizio della prenotazione
    Ora_Fine TIME NOT NULL,           -- Orario di fine della prenotazione
    PRIMARY KEY (ID_Prenotazione, ID_Tavolo, Ora_Inizio),
    FOREIGN KEY (ID_Prenotazione) REFERENCES Prenotazione(ID_Prenotazione)
        ON DELETE CASCADE,
    FOREIGN KEY (ID_Tavolo) REFERENCES Tavoli(ID_Tavolo)
        ON DELETE CASCADE
);

INSERT INTO Tavoli (Numero_Tavolo, Capacita) VALUES 
(1, 2), -- Tavolo 1 con capacità di 2 persone
(2, 2), -- Tavolo 2 con capacità di 2 persone
(3, 2), -- Tavolo 3 con capacità di 2 persone
(4, 2), -- Tavolo 4 con capacità di 2 persone
(5, 4), -- Tavolo 5 con capacità di 4 persone
(6, 4), -- Tavolo 6 con capacità di 4 persone
(7, 4), -- Tavolo 7 con capacità di 4 persone
(8, 4), -- Tavolo 8 con capacità di 4 persone
(9, 6), -- Tavolo 9 con capacità di 6 persone
(10, 6), -- Tavolo 10 con capacità di 6 persone
(11, 6), -- Tavolo 11 con capacità di 6 persone
(12, 6), -- Tavolo 12 con capacità di 6 persone
(13, 2), -- Tavolo 13 con capacità di 2 persone
(14, 2), -- Tavolo 14 con capacità di 2 persone
(15, 4), -- Tavolo 15 con capacità di 4 persone
(16, 6), -- Tavolo 16 con capacità di 6 persone
(17, 10), -- Tavolo 17 con capacità di 10 persone
(18, 10), -- Tavolo 18 con capacità di 10 persone
(19, 10), -- Tavolo 19 con capacità di 10 persone
(20, 20); -- Tavolo 20 con capacità di 20 persone
