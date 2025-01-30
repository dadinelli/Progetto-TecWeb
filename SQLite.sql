DROP TABLE IF EXISTS Cliente;
DROP TABLE IF EXISTS Owner;
DROP TABLE IF EXISTS Cibo;
DROP TABLE IF EXISTS Prenotazione;
DROP TABLE IF EXISTS Recensione;
DROP TABLE IF EXISTS Tavoli;
DROP TABLE IF EXISTS Prenotazione_Tavoli;

CREATE TABLE Cliente (
    ID_Cliente INT AUTO_INCREMENT PRIMARY KEY,
    Nome VARCHAR(100) NOT NULL,
    Cognome VARCHAR(100) NOT NULL,
    Email VARCHAR(150) UNIQUE NOT NULL,
    Telefono VARCHAR(15) UNIQUE,
    Username VARCHAR(100) UNIQUE,
    Pass VARCHAR(100) NOT NULL
);

CREATE TABLE Owner (
    ID_Owner INT PRIMARY KEY,
    Pass VARCHAR(100) NOT NULL
);

-- Tabella Lista di Cibo
CREATE TABLE Cibo (
    ID_Prodotto INT AUTO_INCREMENT PRIMARY KEY,
    Categoria VARCHAR(50) NOT NULL,
    Prezzo DECIMAL(10, 2) NOT NULL,
    IndirizzoDiImmagine VARCHAR(100) NOT NULL,
    Disponibilità BOOLEAN NOT NULL DEFAULT TRUE,
    Caricante INT,
    FOREIGN KEY (Caricante) REFERENCES Owner(ID_Owner) ON DELETE CASCADE ON UPDATE CASCADE
);

-- Tabella Prenotazione
CREATE TABLE Prenotazione (
    ID_Prenotazione INT AUTO_INCREMENT PRIMARY KEY,
    Data DATE NOT NULL,
    Ora TIME NOT NULL,
    Numero_Persone INT NOT NULL,
    Stato VARCHAR(50) NOT NULL,
    ID_Cliente INT NOT NULL,
    ID_Owner INT NOT NULL,
    FOREIGN KEY (ID_Cliente) REFERENCES Cliente(ID_Cliente) ON DELETE CASCADE ON UPDATE CASCADE,
    FOREIGN KEY (ID_Owner) REFERENCES Owner(ID_Owner) ON DELETE CASCADE ON UPDATE CASCADE
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
(13, 2), -- Tavolo 13 con capacità di 7 persone
(14, 2), -- Tavolo 14 con capacità di 7 persone
(15, 4), -- Tavolo 15 con capacità di 7 persone
(16, 6), -- Tavolo 16 con capacità di 7 persone
(17, 10), -- Tavolo 17 con capacità di 10 persone
(18, 10), -- Tavolo 18 con capacità di 10 persone
(19, 10), -- Tavolo 19 con capacità di 10 persone
(20, 20); -- Tavolo 20 con capacità di 20 persone

/*esempio Clienti*/
INSERT INTO Cliente (ID_Cliente, Nome, Cognome, Email, Telefono, Username, Pass) VALUES
(226, 'Mario', 'Rossi', 'mario.rossi@example.com', '1234567890', 'mrossi', 'password123'),
(227, 'Giovanni', 'Verdi', 'giovanni.verdi@example.com', '0987654321', 'gverdi', 'password456'),
(228, 'Luca', 'Bianchi', 'luca.bianchi@example.com', '1122334455', 'lbianchi', 'password789'),
(229, 'Anna', 'Neri', 'anna.neri@example.com', '2233445566', 'aneri', 'password101'),
(230, 'Maria', 'Gialli', 'maria.gialli@example.com', '3334556677', 'mgialli', 'password202'),
(231, 'Paolo', 'Rossi', 'paolo.rossi@example.com', '4445667788', 'prossi', 'password303'),
(232, 'Luigi', 'Verdi', 'luigi.verdi@example.com', '5556778899', 'lverdi', 'password404'),
(233, 'Sara', 'Bianchi', 'sara.bianchi@example.com', '6667889900', 'sbianchi', 'password505'),
(234, 'Francesco', 'Neri', 'francesco.neri@example.com', '7778990011', 'fneri', 'password606'),
(235, 'Martina', 'Gialli', 'martina.gialli@example.com', '8889001122', 'magialli', 'password707'),
(236, 'Roberto', 'Rossi', 'roberto.rossi@example.com', '9990112233', 'rrossi', 'password808'),
(237, 'Giulia', 'Verdi', 'giulia.verdi@example.com', '1011122233', 'giverdi', 'password909'),
(238, 'Stefano', 'Bianchi', 'stefano.bianchi@example.com', '1122334445', 'sebianchi', 'password1001'),
(239, 'Claudia', 'Neri', 'claudia.neri@example.com', '2333445566', 'cneri', 'password2001'),
(240, 'Alessandro', 'Gialli', 'alessandro.gialli@example.com', '3344556677', 'agialli', 'password3001'),
(241, 'Valentina', 'Rossi', 'valentina.rossi@example.com', '4455667788', 'vrossi', 'password4001'),
(242, 'Carlo', 'Verdi', 'carlo.verdi@example.com', '5566778899', 'cverdi', 'password5001'),
(243, 'Elena', 'Bianchi', 'elena.bianchi@example.com', '6677889900', 'ebianchi', 'password6001'),
(244, 'Marco', 'Neri', 'marco.neri@example.com', '7788990011', 'mneri', 'password7001'),
(245, 'Laura', 'Gialli', 'laura.gialli@example.com', '8899001122', 'lgialli', 'password8001');

/*esempio recensioni*/
INSERT INTO Recensione (ID_Recensione, Valutazione, Testo, Data, ID_Cliente) VALUES
(87, 5, 'La pizza era eccellente! Servizio rapido e molto cortese.', '2025-01-01', 226),
(88, 4, 'Pizza molto buona, ma l\'attesa è stata un po\' lunga.', '2025-01-02', 227),
(89, 3, 'La pizza era buona, ma non eccezionale. Il servizio era ok.', '2025-01-03', 228),
(90, 5, 'Un\'esperienza fantastica! Pizza perfetta e ambiente accogliente.', '2025-01-04', 229),
(91, 4, 'Pizza molto buona, ma la scelta dei condimenti potrebbe essere migliore.', '2025-01-05', 230),
(92, 5, 'Una delle migliori pizze che abbia mai mangiato! Ottimo servizio.', '2025-01-06', 231),
(93, 2, 'La pizza non mi ha convinto molto, troppo secca e poco saporita.', '2025-01-07', 232),
(94, 3, 'Pizza nella media, niente di speciale. Servizio abbastanza buono.', '2025-01-08', 233),
(95, 4, 'Pizza buona e ben cotta, ma troppo piccola per il prezzo.', '2025-01-09', 234),
(96, 5, 'Esperienza eccellente! Pizza deliziosa e personale molto gentile.', '2025-01-10', 235),
(97, 4, 'Ottima pizza, ma la pizza margherita potrebbe avere più sapore.', '2025-01-11', 236),
(98, 5, 'Una pizza spettacolare! Sapore intenso e perfetta cottura.', '2025-01-12', 237),
(99, 4, 'Un po\' caro per la qualità, ma comunque una pizza buona.', '2025-01-13', 238),
(100, 3, 'La pizza era ok, ma non mi ha entusiasmato. Il servizio era buono.', '2025-01-14', 239),
(101, 5, 'Pizzeria fantastica! La pizza era perfetta e il personale molto gentile.', '2025-01-15', 240),
(102, 4, 'Pizza buona, ma un po\' troppo salata per i miei gusti.', '2025-01-16', 241),
(103, 2, 'La pizza non era ben cotta, e il servizio era molto lento.', '2025-01-17', 242),
(104, 3, 'Non male, ma mi aspettavo di più. Il servizio è stato ok.', '2025-01-18', 243),
(105, 4, 'Pizzeria molto accogliente, la pizza era buona anche se un po\' piccola.', '2025-01-19', 244),
(106, 5, 'Pizzeria top! La pizza era veramente ottima e la qualità eccellente.', '2025-01-20', 245);