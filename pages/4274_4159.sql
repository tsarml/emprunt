CREATE TABLE membre (
    id_membre INT AUTO_INCREMENT PRIMARY KEY,
    nom VARCHAR(255) NOT NULL,
    date_de_naissance DATE,
    genre CHAR(10),
    email VARCHAR(255) NOT NULL,
    ville VARCHAR(255),
    mdp VARCHAR(255) NOT NULL,
    image_profil VARCHAR(255)
);

CREATE TABLE categorie_objet (
    id_categorie INT AUTO_INCREMENT PRIMARY KEY,
    nom_categorie VARCHAR(255) NOT NULL
);

CREATE TABLE objet (
    id_objet INT AUTO_INCREMENT PRIMARY KEY,
    nom_objet VARCHAR(255) NOT NULL,
    id_categorie INT,
    id_membre INT,
    FOREIGN KEY (id_categorie) REFERENCES categorie_objet(id_categorie),
    FOREIGN KEY (id_membre) REFERENCES membre(id_membre)
);

CREATE TABLE image_objet(
    id_image INT AUTO_INCREMENT PRIMARY KEY,
    id_objet INT,
    nom_image VARCHAR(255)
);

CREATE TABLE emprunt (
    id_emprunt INT AUTO_INCREMENT PRIMARY KEY,
    id_objet INT,
    id_membre INT,
    date_emprunt DATE NOT NULL,
    date_retour DATE,
    FOREIGN KEY (id_objet) REFERENCES objet(id_objet),
    FOREIGN KEY (id_membre) REFERENCES membre(id_membre)
);



INSERT INTO membre (nom, date_de_naissance, genre, email, ville, mdp, image_profil) VALUES
('Dupont', '1990-03-15', 'Homme', 'jean.dupont@email.com', 'Paris', 'motdepasse123', 'profil_jean.jpg'),
('Martin', '1988-07-22', 'Femme', 'sophie.martin@email.com', 'Lyon', 'sophie456', 'profil_sophie.jpg'),
('Lefevre', '1995-11-30', 'Homme', 'paul.lefevre@email.com', 'Marseille', 'paul789', 'profil_paul.jpg'),
('Durand', '1992-04-10', 'Femme', 'marie.durand@email.com', 'Toulouse', 'marie101', 'profil_marie.jpg');

INSERT INTO categorie_objet (nom_categorie) VALUES
('Esthétique'),
('Bricolage'),
('Mécanique'),
('Cuisine');


INSERT INTO objet (nom_objet, id_membre, id_categorie) VALUES
('Sèche-cheveux', 1, 1),
('Masque visage', 1, 1),
('Parfum', 1, 1),
('Perceuse', 1, 2),
('Marteau', 1, 2),
('Tournevis', 1, 2),
('Clé à molette', 1, 3),
('Pompe à vélo', 1, 3),
('Mixeur', 1, 4),
('Casserole', 1, 4),

('Fer à lisser', 2, 1),
('Crème hydratante', 2, 1),
('Maquillage', 2, 1),
('Scie', 2, 2),
('Niveau à bulle', 2, 2),
('Pinceau', 2, 2),
('Cric auto', 2, 3),
('Clé dynamométrique', 2, 3),
('Robot cuisine', 2, 4),
('Poêle', 2, 4),

('Épilateur', 3, 1),
('Lime à ongles', 3, 1),
('Sérum visage', 3, 1),
('Visseuse', 3, 2),
('Échelle', 3, 2),
('Boîte à outils', 3, 2),
('Compresseur', 3, 3),
('Manomètre', 3, 3),
('Blender', 3, 4),
('Moule à gâteau', 3, 4),

('Curling', 4, 1),
('Vernis', 4, 1),
('Masque capillaire', 4, 1),
('Ponceuse', 4, 2),
('Mètre ruban', 4, 2),
('Cutter', 4, 2),
('Clé à choc', 4, 3),
('Testeur batterie', 4, 3),
('Hachoir', 4, 4),
('Plancha', 4, 4);


INSERT INTO emprunt (id_objet, id_membre, date_emprunt, date_retour) VALUES
(1, 2, '2025-07-01', '2025-07-05'), 
(5, 3, '2025-07-02', NULL),       
(11, 4, '2025-07-03', '2025-07-07'),  
(15, 1, '2025-07-04', NULL),       
(21, 2, '2025-07-05', '2025-07-10'), 
(25, 4, '2025-07-06', NULL),       
(31, 3, '2025-07-07', '2025-07-12'), 
(35, 1, '2025-07-08', NULL),       
(12, 3, '2025-07-09', '2025-07-13'), 
(28, 2, '2025-07-10', NULL);       


INSERT INTO image_objet (id_objet, nom_image) VALUES
(1, 'seche_cheveux.jpg'),
(2, 'masque_visage.jpg'),
(3, 'parfum.jpg'),
(4, 'perceuse.jpg'),
(5, 'marteau.jpg'),
(6, 'tournevis.jpg'),
(7, 'cle_a_molette.jpg'),
(8, 'pompe_a_velo.jpg'),
(9, 'mixeur.jpg'),
(10, 'casserole.jpg'),

(11, 'fer_a_lisser.jpg'),
(12, 'creme_hydratante.jpg'),
(13, 'maquillage.jpg'),
(14, 'scie.jpg'),
(15, 'niveau_a_bulle.jpg'),
(16, 'pinceau.jpg'),
(17, 'cric_auto.jpg'),
(18, 'cle_dynamometrique.jpg'),
(19, 'robot_cuisine.jpg'),
(20, 'poele.jpg'),

(21, 'epilateur.jpg'),
(22, 'lime_a_ongles.jpg'),
(23, 'serum_visage.jpg'),
(24, 'visseuse.jpg'),
(25, 'echelle.jpg'),
(26, 'boite_a_outils.jpg'),
(27, 'compresseur.jpg'),
(28, 'manometre.jpg'),
(29, 'blender.jpg'),
(30, 'moule_a_gateau.jpg'),

(31, 'curling.jpg'),
(32, 'vernis.jpg'),
(33, 'masque_capillaire.jpg'),
(34, 'ponceuse.jpg'),
(35, 'metre_ruban.jpg'),
(36, 'cutter.jpg'),
(37, 'cle_a_choc.jpg'),
(38, 'testeur_batterie.jpg'),
(39, 'hachoir.jpg'),
(40, 'plancha.jpg');

ALTER TABLE image_objet ADD est_principale BOOLEAN DEFAULT FALSE;