-- Table villes
CREATE TABLE villes (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nom VARCHAR(255) NOT NULL
);


CREATE TABLE formateurs (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nom VARCHAR(255) NOT NULL,
    prenom VARCHAR(255) NOT NULL,
    prenom_arab VARCHAR(255),
    nom_arab VARCHAR(255),
    tele_num VARCHAR(15),
    profile_image VARCHAR(255)
);




-- Table personnes
CREATE TABLE apprenants (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nom VARCHAR(255) NOT NULL,
    prenom VARCHAR(255) NOT NULL,
    prenom_arab VARCHAR(255),
    nom_arab VARCHAR(255),
    tele_num VARCHAR(15),
    profile_image VARCHAR(255),
    date_inscription DATE,
    ville_id INT,
    niveaux_scolaires_id INT,
    groupe_id INT,
    FOREIGN KEY (ville_id) REFERENCES villes(id),
    FOREIGN KEY (groupe_id) REFERENCES groupes(id),
    FOREIGN KEY (niveaux_scolaires_id) REFERENCES niveaux_scolaires(id)
);


-- Table groupes
CREATE TABLE groupes (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nom VARCHAR(255) NOT NULL,
    description TEXT
);

-- Table specialites
CREATE TABLE specialites (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nom VARCHAR(255) NOT NULL,
    description TEXT
);

-- Table niveaux_scolaires
CREATE TABLE niveaux_scolaires (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nom VARCHAR(255) NOT NULL,
    description TEXT
);


-- Relation Many-to-Many formateurs <-> groupes
CREATE TABLE formateur_groupe (
    formateur_id INT,
    groupe_id INT,
    PRIMARY KEY (formateur_id, groupe_id),
    FOREIGN KEY (formateur_id) REFERENCES formateurs(id),
    FOREIGN KEY (groupe_id) REFERENCES groupes(id)
);

-- Relation Many-to-Many apprenants <-> groupes
CREATE TABLE apprenant_groupe (
    apprenant_id BIGINT UNSIGNED,
    groupe_id BIGINT UNSIGNED,
    PRIMARY KEY (apprenant_id, groupe_id),
    FOREIGN KEY (apprenant_id) REFERENCES apprenants(id),
    FOREIGN KEY (groupe_id) REFERENCES groupes(id)
);

-- Relation Many-to-Many formateurs <-> specialites
CREATE TABLE formateur_specialite (
    formateur_id BIGINT UNSIGNED,
    specialite_id BIGINT UNSIGNED,
    PRIMARY KEY (formateur_id, specialite_id),
    FOREIGN KEY (formateur_id) REFERENCES formateurs(id),
    FOREIGN KEY (specialite_id) REFERENCES specialites(id)
);
