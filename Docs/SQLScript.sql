-- ================================================================
-- SCRIPT SQL - CATALOGUE TECHNIQUE ALUSTOCK
-- Tables : Gamme, Categorie, Ouvrage, Composant, Finition, etc.
-- Version : V2 (orientée métier)
-- ================================================================

-- 1. GAMME (famille de produits)
CREATE TABLE gammes (
    id INT PRIMARY KEY AUTO_INCREMENT,
    nom VARCHAR(100) NOT NULL UNIQUE,
    slug VARCHAR(120) NOT NULL UNIQUE,
    description TEXT NULL,
    image_cover VARCHAR(255) NULL,
    ordre_affichage INT DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP NULL ON UPDATE CURRENT_TIMESTAMP
);

-- 2. CATEGORIE (type d'ouvrage fonctionnel)
CREATE TABLE categories (
    id INT PRIMARY KEY AUTO_INCREMENT,
    nom VARCHAR(100) NOT NULL UNIQUE,
    slug VARCHAR(120) NOT NULL UNIQUE,
    description TEXT NULL,
    icone VARCHAR(50) NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP NULL ON UPDATE CURRENT_TIMESTAMP
);

-- 3. TYPE_COMPOSANT (nature du composant)
CREATE TABLE types_composant (
    id INT PRIMARY KEY AUTO_INCREMENT,
    nom VARCHAR(100) NOT NULL UNIQUE,
    slug VARCHAR(120) NOT NULL UNIQUE,
    description TEXT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP NULL ON UPDATE CURRENT_TIMESTAMP
);

-- 4. OUVRAGE (produit fini)
CREATE TABLE ouvrages (
    id INT PRIMARY KEY AUTO_INCREMENT,
    reference VARCHAR(50) NOT NULL UNIQUE,
    nom VARCHAR(200) NOT NULL,
    slug VARCHAR(220) NOT NULL UNIQUE,
    gamme_id INT NOT NULL,
    categorie_id INT NOT NULL,
    description_courte TEXT NULL,
    description_technique LONGTEXT NULL,
    largeur_min_mm INT NULL,
    largeur_max_mm INT NULL,
    hauteur_min_mm INT NULL,
    hauteur_max_mm INT NULL,
    performance_thermique VARCHAR(50) NULL,
    performance_acoustique VARCHAR(50) NULL,
    image_principale VARCHAR(255) NULL,
    est_actif BOOLEAN DEFAULT TRUE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP NULL ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (gamme_id) REFERENCES gammes(id) ON DELETE CASCADE,
    FOREIGN KEY (categorie_id) REFERENCES categories(id) ON DELETE CASCADE
);

-- Index pour ouvrages
CREATE INDEX idx_ouvrages_gamme ON ouvrages(gamme_id);
CREATE INDEX idx_ouvrages_categorie ON ouvrages(categorie_id);
CREATE INDEX idx_ouvrages_actif ON ouvrages(est_actif);
CREATE INDEX idx_ouvrages_reference ON ouvrages(reference);

-- 5. COMPOSANT (pièce technique)
CREATE TABLE composants (
    id INT PRIMARY KEY AUTO_INCREMENT,
    reference VARCHAR(50) NOT NULL UNIQUE,
    designation VARCHAR(200) NOT NULL,
    slug VARCHAR(220) NOT NULL UNIQUE,
    type_composant_id INT NOT NULL,
    gamme_id INT NULL,
    matiere VARCHAR(100) NULL,
    longueur_barre_mm INT NULL,
    poids_lineaire_kg_m DECIMAL(10,3) NULL,
    section_largeur_mm DECIMAL(10,2) NULL,
    section_hauteur_mm DECIMAL(10,2) NULL,
    epaisseur_paroi_mm DECIMAL(10,2) NULL,
    moment_inertie_x_cm4 DECIMAL(10,2) NULL,
    moment_inertie_y_cm4 DECIMAL(10,2) NULL,
    module_elasticite_x_cm3 DECIMAL(10,2) NULL,
    module_elasticite_y_cm3 DECIMAL(10,2) NULL,
    image_coupe VARCHAR(255) NULL,
    est_disponible BOOLEAN DEFAULT TRUE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP NULL ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (type_composant_id) REFERENCES types_composant(id) ON DELETE CASCADE,
    FOREIGN KEY (gamme_id) REFERENCES gammes(id) ON DELETE SET NULL
);

-- Index pour composants
CREATE INDEX idx_composants_type ON composants(type_composant_id);
CREATE INDEX idx_composants_gamme ON composants(gamme_id);
CREATE INDEX idx_composants_disponible ON composants(est_disponible);
CREATE INDEX idx_composants_reference ON composants(reference);

-- 6. COMPOSITION_OUVRAGE (table de liaison Ouvrage ↔ Composant)
CREATE TABLE composition_ouvrage (
    ouvrage_id INT NOT NULL,
    composant_id INT NOT NULL,
    quantite DECIMAL(10,2) NOT NULL DEFAULT 1,
    unite VARCHAR(20) NOT NULL DEFAULT 'u',
    ordre INT DEFAULT 0,
    longueur_coupe_mm INT NULL,
    commentaire TEXT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP NULL ON UPDATE CURRENT_TIMESTAMP,
    PRIMARY KEY (ouvrage_id, composant_id),
    FOREIGN KEY (ouvrage_id) REFERENCES ouvrages(id) ON DELETE CASCADE,
    FOREIGN KEY (composant_id) REFERENCES composants(id) ON DELETE CASCADE
);

-- 7. FINITION
CREATE TABLE finitions (
    id INT PRIMARY KEY AUTO_INCREMENT,
    nom VARCHAR(100) NOT NULL UNIQUE,
    slug VARCHAR(120) NOT NULL UNIQUE,
    code_ral VARCHAR(10) NULL,
    type_finition ENUM('laquage','anodisation','brut','poudre') NOT NULL,
    description TEXT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP NULL ON UPDATE CURRENT_TIMESTAMP
);

-- 8. COMPOSANT_FINITION (table de liaison Composant ↔ Finition)
CREATE TABLE composant_finition (
    composant_id INT NOT NULL,
    finition_id INT NOT NULL,
    est_par_defaut BOOLEAN DEFAULT FALSE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (composant_id, finition_id),
    FOREIGN KEY (composant_id) REFERENCES composants(id) ON DELETE CASCADE,
    FOREIGN KEY (finition_id) REFERENCES finitions(id) ON DELETE CASCADE
);

-- 9. CARACTERISTIQUE (EAV - Entité Attribut Valeur)
CREATE TABLE caracteristiques (
    id INT PRIMARY KEY AUTO_INCREMENT,
    composant_id INT NOT NULL,
    cle VARCHAR(100) NOT NULL,
    valeur VARCHAR(255) NOT NULL,
    unite VARCHAR(20) NULL,
    ordre_affichage INT DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP NULL ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (composant_id) REFERENCES composants(id) ON DELETE CASCADE
);

-- Index pour caracteristiques
CREATE INDEX idx_caracteristiques_composant ON caracteristiques(composant_id);
CREATE INDEX idx_caracteristiques_cle ON caracteristiques(cle);

-- 10. MEDIA
CREATE TABLE medias (
    id INT PRIMARY KEY AUTO_INCREMENT,
    chemin_fichier VARCHAR(255) NOT NULL,
    titre VARCHAR(200) NULL,
    description TEXT NULL,
    type_media ENUM('image','rendu_3d','plan','video') NOT NULL,
    est_principal BOOLEAN DEFAULT FALSE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP NULL ON UPDATE CURRENT_TIMESTAMP
);

-- 11. MEDIA_MORPH (Polymorphique)
CREATE TABLE media_morph (
    media_id INT NOT NULL,
    mediable_id INT NOT NULL,
    mediable_type VARCHAR(100) NOT NULL,
    ordre INT DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (media_id, mediable_id, mediable_type),
    FOREIGN KEY (media_id) REFERENCES medias(id) ON DELETE CASCADE
);

-- Index pour media_morph
CREATE INDEX idx_media_morph_target ON media_morph(mediable_id, mediable_type);

-- 12. DOCUMENT
CREATE TABLE documents (
    id INT PRIMARY KEY AUTO_INCREMENT,
    chemin_fichier VARCHAR(255) NOT NULL,
    titre VARCHAR(200) NOT NULL,
    description TEXT NULL,
    type_document ENUM('plan','fiche_technique','certificat','notice','autre') NOT NULL,
    taille_octets INT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP NULL ON UPDATE CURRENT_TIMESTAMP
);

-- 13. DOCUMENT_ASSOCIATION
CREATE TABLE document_association (
    document_id INT NOT NULL,
    ouvrage_id INT NULL,
    composant_id INT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (document_id) REFERENCES documents(id) ON DELETE CASCADE,
    FOREIGN KEY (ouvrage_id) REFERENCES ouvrages(id) ON DELETE CASCADE,
    FOREIGN KEY (composant_id) REFERENCES composants(id) ON DELETE CASCADE,
    CHECK (ouvrage_id IS NOT NULL OR composant_id IS NOT NULL)
);

-- Index pour document_association
CREATE INDEX idx_doc_assoc_ouvrage ON document_association(ouvrage_id);
CREATE INDEX idx_doc_assoc_composant ON document_association(composant_id);