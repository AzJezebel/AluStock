-- 1. GAMME
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

-- 2. TYPE_OUVRAGE
CREATE TABLE types_ouvrage (
    id INT PRIMARY KEY AUTO_INCREMENT,
    nom VARCHAR(100) NOT NULL UNIQUE,
    slug VARCHAR(120) NOT NULL UNIQUE,
    description TEXT NULL,
    icone VARCHAR(50) NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP NULL ON UPDATE CURRENT_TIMESTAMP
);

-- 3. MODELE
CREATE TABLE modeles (
    id INT PRIMARY KEY AUTO_INCREMENT,
    reference VARCHAR(50) NOT NULL UNIQUE,
    nom VARCHAR(200) NOT NULL,
    slug VARCHAR(220) NOT NULL UNIQUE,
    gamme_id INT NOT NULL,
    type_ouvrage_id INT NOT NULL,
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
    FOREIGN KEY (type_ouvrage_id) REFERENCES types_ouvrage(id) ON DELETE CASCADE
);

-- Index pour modeles
CREATE INDEX idx_modeles_gamme ON modeles(gamme_id);
CREATE INDEX idx_modeles_type ON modeles(type_ouvrage_id);
CREATE INDEX idx_modeles_actif ON modeles(est_actif);

-- 4. TYPE_PIECE
CREATE TABLE types_piece (
    id INT PRIMARY KEY AUTO_INCREMENT,
    nom VARCHAR(100) NOT NULL UNIQUE,
    slug VARCHAR(120) NOT NULL UNIQUE,
    description TEXT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP NULL ON UPDATE CURRENT_TIMESTAMP
);

-- 5. PIECE
CREATE TABLE pieces (
    id INT PRIMARY KEY AUTO_INCREMENT,
    reference VARCHAR(50) NOT NULL UNIQUE,
    designation VARCHAR(200) NOT NULL,
    slug VARCHAR(220) NOT NULL UNIQUE,
    type_piece_id INT NOT NULL,
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
    FOREIGN KEY (type_piece_id) REFERENCES types_piece(id) ON DELETE CASCADE,
    FOREIGN KEY (gamme_id) REFERENCES gammes(id) ON DELETE SET NULL
);

-- Index pour pieces
CREATE INDEX idx_pieces_type ON pieces(type_piece_id);
CREATE INDEX idx_pieces_gamme ON pieces(gamme_id);
CREATE INDEX idx_pieces_disponible ON pieces(est_disponible);
CREATE INDEX idx_pieces_reference ON pieces(reference);

-- 6. COMPOSITION_MODELE
CREATE TABLE composition_modele (
    modele_id INT NOT NULL,
    piece_id INT NOT NULL,
    quantite DECIMAL(10,2) NOT NULL DEFAULT 1,
    unite VARCHAR(20) NOT NULL DEFAULT 'u',
    ordre INT DEFAULT 0,
    longueur_coupe_mm INT NULL,
    commentaire TEXT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP NULL ON UPDATE CURRENT_TIMESTAMP,
    PRIMARY KEY (modele_id, piece_id),
    FOREIGN KEY (modele_id) REFERENCES modeles(id) ON DELETE CASCADE,
    FOREIGN KEY (piece_id) REFERENCES pieces(id) ON DELETE CASCADE
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

-- 8. PIECE_FINITION
CREATE TABLE piece_finition (
    piece_id INT NOT NULL,
    finition_id INT NOT NULL,
    est_par_defaut BOOLEAN DEFAULT FALSE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (piece_id, finition_id),
    FOREIGN KEY (piece_id) REFERENCES pieces(id) ON DELETE CASCADE,
    FOREIGN KEY (finition_id) REFERENCES finitions(id) ON DELETE CASCADE
);

-- 9. CARACTERISTIQUE (EAV)
CREATE TABLE caracteristiques (
    id INT PRIMARY KEY AUTO_INCREMENT,
    piece_id INT NOT NULL,
    cle VARCHAR(100) NOT NULL,
    valeur VARCHAR(255) NOT NULL,
    unite VARCHAR(20) NULL,
    ordre_affichage INT DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP NULL ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (piece_id) REFERENCES pieces(id) ON DELETE CASCADE
);

-- Index pour caracteristiques
CREATE INDEX idx_caracteristiques_piece ON caracteristiques(piece_id);
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
    modele_id INT NULL,
    piece_id INT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (document_id) REFERENCES documents(id) ON DELETE CASCADE,
    FOREIGN KEY (modele_id) REFERENCES modeles(id) ON DELETE CASCADE,
    FOREIGN KEY (piece_id) REFERENCES pieces(id) ON DELETE CASCADE,
    CHECK (modele_id IS NOT NULL OR piece_id IS NOT NULL)
);

-- Index pour document_association
CREATE INDEX idx_doc_assoc_modele ON document_association(modele_id);
CREATE INDEX idx_doc_assoc_piece ON document_association(piece_id);