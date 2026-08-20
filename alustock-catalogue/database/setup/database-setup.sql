-- ============================================================
-- SCRIPT DE CRÉATION DE LA BASE DE DONNÉES - ALUSTOCK
-- À exécuter avant les migrations Laravel
-- ============================================================

-- 1. Création de la base de données
CREATE DATABASE IF NOT EXISTS alustock_catalogue
    CHARACTER SET utf8mb4
    COLLATE utf8mb4_unicode_ci;

-- 2. Utilisateur pour le développement
CREATE USER IF NOT EXISTS 'alustock_dev'@'localhost' 
    IDENTIFIED BY 'dev_password';

-- 3. Utilisateur pour les tests
CREATE USER IF NOT EXISTS 'alustock_test'@'localhost' 
    IDENTIFIED BY 'test_password';

-- 4. Attribution des droits - Développement
GRANT ALL PRIVILEGES ON alustock_catalogue.* TO 'alustock_dev'@'localhost';

-- 5. Attribution des droits - Tests (moins de droits)
GRANT SELECT, INSERT, UPDATE, DELETE ON alustock_catalogue.* TO 'alustock_test'@'localhost';

-- 6. Application immédiate
FLUSH PRIVILEGES;

-- 7. Sélection de la base
USE alustock_catalogue;

-- ============================================================
-- Correspondance pour le fichier .env
-- ============================================================
-- Mode DEV :
-- DB_DATABASE=alustock_catalogue
-- DB_USERNAME=alustock_dev
-- DB_PASSWORD=dev_password
--
-- Mode TEST (php artisan test) :
-- DB_DATABASE=alustock_catalogue
-- DB_USERNAME=alustock_test
-- DB_PASSWORD=test_password
-- ============================================================