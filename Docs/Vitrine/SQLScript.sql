CREATE DATABASE IF NOT EXISTS alustock_vitrine
CHARACTER SET utf8mb4
COLLATE utf8mb4_unicode_ci;

USE alustock_vitrine;

-- Tables principales
CREATE TABLE categories (
    id BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    nom VARCHAR(255) NOT NULL,
    slug VARCHAR(255) NOT NULL UNIQUE,
    description TEXT NULL,
    icone VARCHAR(100) NULL DEFAULT 'fa-cube',
    is_active TINYINT(1) NOT NULL DEFAULT 1,
    ordre INT NOT NULL DEFAULT 0,
    created_at TIMESTAMP NULL,
    updated_at TIMESTAMP NULL,
    PRIMARY KEY (id)
);

CREATE TABLE gammes (
    id BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    nom VARCHAR(255) NOT NULL,
    slug VARCHAR(255) NOT NULL UNIQUE,
    description TEXT NULL,
    icone VARCHAR(100) NULL DEFAULT 'fa-cubes',
    couleur VARCHAR(50) NULL DEFAULT '#F59E0B',
    is_active TINYINT(1) NOT NULL DEFAULT 1,
    ordre INT NOT NULL DEFAULT 0,
    created_at TIMESTAMP NULL,
    updated_at TIMESTAMP NULL,
    PRIMARY KEY (id)
);

CREATE TABLE ouvrages (
    id BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    titre VARCHAR(255) NOT NULL,
    slug VARCHAR(255) NOT NULL UNIQUE,
    description TEXT NULL,
    reference VARCHAR(255) NOT NULL UNIQUE,
    categorie_id BIGINT UNSIGNED NULL,
    gamme_id BIGINT UNSIGNED NULL,
    image VARCHAR(255) NULL,
    images TEXT NULL,
    date_realisation DATE NULL,
    client VARCHAR(255) NULL,
    localisation VARCHAR(255) NULL,
    specifications TEXT NULL,
    is_active TINYINT(1) NOT NULL DEFAULT 1,
    is_featured TINYINT(1) NOT NULL DEFAULT 0,
    views INT NOT NULL DEFAULT 0,
    created_at TIMESTAMP NULL,
    updated_at TIMESTAMP NULL,
    PRIMARY KEY (id),
    FOREIGN KEY (categorie_id) REFERENCES categories(id) ON DELETE SET NULL,
    FOREIGN KEY (gamme_id) REFERENCES gammes(id) ON DELETE SET NULL
);
