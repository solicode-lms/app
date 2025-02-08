
 
-- Table filieres
CREATE TABLE filieres (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    nom VARCHAR(255) NOT NULL,
    description VARCHAR(255)
);


-- Table modules
CREATE TABLE modules (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    nom VARCHAR(255) NOT NULL,
    description VARCHAR(255),
    masse_horaire VARCHAR(50),
    filiere_id BIGINT UNSIGNED,
    FOREIGN KEY (filiere_id) REFERENCES filieres(id) ON DELETE CASCADE
);

-- Table competences
CREATE TABLE competences (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    code VARCHAR(255) NOT NULL,
    nom VARCHAR(255) NOT NULL,
    description TEXT,
    module_id BIGINT UNSIGNED,
    FOREIGN KEY (module_id) REFERENCES modules(id) ON DELETE CASCADE
);

-- Table niveau_competences
CREATE TABLE niveau_competences (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    nom VARCHAR(255) NOT NULL,
    description VARCHAR(255),
    competence_id BIGINT UNSIGNED,
    FOREIGN KEY (competence_id) REFERENCES competences(id) ON DELETE CASCADE
);

-- Table categorie_technologies
CREATE TABLE categorie_technologies (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    nom VARCHAR(255) NOT NULL,
    description VARCHAR(255)
);

-- Table technologies
CREATE TABLE technologies (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    nom VARCHAR(255) NOT NULL,
    description VARCHAR(255),
    categorie_technologie_id BIGINT UNSIGNED,
    FOREIGN KEY (categorie_technologie_id) REFERENCES categorie_technologies(id) ON DELETE SET NULL
);



-- Table competence_technologie (relation many-to-many)
CREATE TABLE competence_technologie (
    competence_id BIGINT UNSIGNED NOT NULL,
    technologie_id BIGINT UNSIGNED NOT NULL,
    PRIMARY KEY (competence_id, technologie_id),
    FOREIGN KEY (competence_id) REFERENCES competences(id) ON DELETE CASCADE,
    FOREIGN KEY (technologie_id) REFERENCES technologies(id) ON DELETE CASCADE
);

-- Table appreciations
CREATE TABLE appreciations (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    nom VARCHAR(255) NOT NULL,
    description VARCHAR(255),
    note_min FLOAT NOT NULL,
    note_max FLOAT NOT NULL,
    niveau_competence_id BIGINT UNSIGNED NOT NULL,
    formateur_id BIGINT UNSIGNED,
    FOREIGN KEY (niveau_competence_id) REFERENCES niveau_competences(id) ON DELETE CASCADE,
    FOREIGN KEY (formateur_id) REFERENCES formateurs(id) ON DELETE SET NULL
);


