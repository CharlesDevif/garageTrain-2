CREATE DATABASE IF NOT EXISTS garage_vroum CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

USE garage_vroum;

START TRANSACTION;

-- Table des clients
CREATE TABLE IF NOT EXISTS clients (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nom VARCHAR(50) NOT NULL,
    email VARCHAR(50),
    telephone VARCHAR(15)
);

-- Table des véhicules
CREATE TABLE IF NOT EXISTS vehicules (
    id INT AUTO_INCREMENT PRIMARY KEY,
    marque VARCHAR(50) NOT NULL,
    modele VARCHAR(50) NOT NULL,
    annee INT,
    plaque VARCHAR(15) NOT NULL UNIQUE, -- Plaque d'immatriculation unique
    client_id INT,
    FOREIGN KEY (client_id) REFERENCES clients(id)
);

-- Table des rendez-vous
CREATE TABLE IF NOT EXISTS rendezvous (
    id INT AUTO_INCREMENT PRIMARY KEY,
    date_heure DATETIME NOT NULL,
    vehicule_id INT,
    description TEXT,
    FOREIGN KEY (vehicule_id) REFERENCES vehicules(id) ON DELETE CASCADE
);

-- Table des administrateurs
CREATE TABLE IF NOT EXISTS administrateurs (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL,
    email VARCHAR(100) NOT NULL UNIQUE, -- Email unique
    password_hash VARCHAR(255) NOT NULL,
    is_admin TINYINT(1) DEFAULT 0 -- 0 = Non, 1 = Oui
);

-- Table des tokens
CREATE TABLE IF NOT EXISTS tokens (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT,
    token VARCHAR(255) NOT NULL,
    expiration_date DATETIME NOT NULL,
    FOREIGN KEY (user_id) REFERENCES administrateurs(id)
);

-- Insérer des administrateurs
INSERT INTO administrateurs (username, email, password_hash, is_admin) VALUES
('admin1', 'admin1@garagevroum.com', '$2y$10$ZEt8W8R5ruBzKbqKE6D9Guypivb2QGN/11gwD74AkjDzpTt7XRik6', 1),
('admin2', 'admin2@garagevroum.com', '$2y$10$TOBO0ipevsQEWJ7oME7iEegjPT7s3HL9K5PJB.qIiXwj1ED2ZhvTi', 0);

-- Insérer des clients
INSERT INTO clients (nom, email, telephone) VALUES
('John Doe', 'john.doe@email.com', '123456789'),
('Jane Smith', 'jane.smith@email.com', '987654321'),
('Bob Johnson', 'bob.johnson@email.com', '555555555');

-- Insérer des véhicules
INSERT INTO vehicules (marque, modele, annee, plaque, client_id) VALUES
('Toyota', 'Camry', 2015, 'AA-123-AA', 1),
('Honda', 'Civic', 2020, 'BB-456-BB', 2),
('Ford', 'Focus', 2018, 'CC-789-CC', 3);

-- Insérer des rendez-vous
INSERT INTO rendezvous (date_heure, vehicule_id, description) VALUES
('2023-12-01 10:00:00', 1, "Entretien régulier"),
('2023-12-15 14:30:00', 2, "Changement d'huile"),
('2023-12-20 09:15:00', 3, "Diagnostic moteur");

-- Insérer un token pour test
INSERT INTO tokens (user_id, token, expiration_date) VALUES
(1, 'abcdef12faketoken3456', '2023-12-31 23:59:59');

COMMIT;
