CREATE DATABASE IF NOT EXISTS exam;

USE exam;

CREATE TABLE membre (
    id_membre INT PRIMARY KEY AUTO_INCREMENT,
    nom VARCHAR(255),
    numero_etu INT UNIQUE,
    image_profil VARCHAR(255)
);

CREATE TABLE categorie (
    id_categorie INT PRIMARY KEY AUTO_INCREMENT,
    nom_categorie VARCHAR(255)
);

CREATE TABLE produit (
    id_produit INT PRIMARY KEY AUTO_INCREMENT,
    nom VARCHAR(255),
    id_categorie INT,
    prix_reference DECIMAL(10, 2),
    FOREIGN KEY (id_categorie) REFERENCES categorie(id_categorie)
);

CREATE TABLE produit_membre (
    id_produit_membre INT PRIMARY KEY AUTO_INCREMENT,
    id_produit INT,
    id_membre INT,
    prix_vente DECIMAL(10, 2),
    quantite_dispo INT,
    date_dispo DATE,
    FOREIGN KEY (id_produit) REFERENCES produit(id_produit),
    FOREIGN KEY (id_membre) REFERENCES membre(id_membre)
);

CREATE TABLE vente (
    id_vente INT PRIMARY KEY AUTO_INCREMENT,
    date DATE,
    heure TIME,
    id_produit_membre INT,
    quantite INT,
    FOREIGN KEY (id_produit_membre) REFERENCES produit_membre(id_produit_membre)
);

INSERT INTO membre (nom, numero_etu) VALUES 
('Ralay', 2664),
('Bob', 1020),
('Charlie', 4767),
('Davy', 1234),
('Ezy', 5678),
('Fay', 9101),
('Gina', 1121),
('Hank', 3141),
('Ivy', 5161),
('Jack', 7181);

INSERT INTO categorie (nom_categorie) VALUES 
('plat'),
('boisson'),
('snack'),
('dessert');

INSERT INTO produit (nom, id_categorie, prix_reference) VALUES 
('Pizza', 1, 22000.00),
('Burger', 1, 5000.00),
('Coca-Cola', 2, 200.00),
('Chips', 3, 500.00),
('Ice Cream', 4, 4999.99),
('Carbonara', 1, 17000.00),
('Water', 2, 3000.50),
('Candy', 3, 500.00),
('Cake', 4, 6000.00),
('Sedap', 1, 3000.00),
('Juice', 2, 100.00),
('Popcorn', 3, 500.00),
('Brownie', 4, 4500.00),
('Cafe', 2, 200.00),
('Mogule', 3, 2000.00);

INSERT INTO produit_membre (id_produit, id_membre, prix_vente, quantite_dispo, date_dispo) VALUES 
(1, 1, 25000.00, 10, '2026-06-01'),
(2, 2, 6000.00, 15, '2026-06-02'),
(3, 3, 250.00, 20, '2026-06-03'),
(4, 4, 600.00, 25, '2026-06-04'),
(5, 5, 5500.00, 30, '2026-06-05'),
(6, 6, 18000.00, 12, '2026-06-06'),
(7, 7, 3500.50, 18, '2026-06-07'),
(8, 8, 600.00, 22, '2026-06-08'),
(9, 9, 6500.00, 28, '2026-06-09'),
(10, 10, 3500.00, 14, '2026-06-10'),
(11, 1, 3200.00, 16, '2026-06-11'),
(12, 2, 150.00, 19, '2026-06-12'),
(13, 3, 550.00, 21, '2026-06-13'),
(14, 4, 5000.00, 24, '2026-06-14'),
(15, 5, 2200.00, 26, '2026-06-15'),
(10, 6, 3000.00, 20, '2026-06-16'),
(11, 7, 400.00, 18, '2026-06-17'),
(12, 8, 600.00, 22, '2026-06-18'),
(13, 9, 7000.00, 28, '2026-06-19'),
(14, 10, 3500.00, 14, '2026-06-20');


ALTER TABLE produit_membre ADD COLUMN image VARCHAR(255) AFTER date_dispo;
UPDATE produit_membre SET image = 'plat_default.jpg' WHERE id_produit IN (1, 2, 6, 10);
UPDATE produit_membre SET image = 'boisson_default.jpg' WHERE id_produit IN (3, 7, 11, 14);
UPDATE produit_membre SET image = 'snak_default.jpg' WHERE id_produit IN (4, 8, 12, 15);
UPDATE produit_membre SET image = 'dessert_default.jpg' WHERE id_produit IN (5, 9, 13);

ALTER TABLE produit ADD CULUMN perime BOOLEAN DEFAULT FALSE AFTER prix_reference;