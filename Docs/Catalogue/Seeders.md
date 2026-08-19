# Catalogue

database/seeders/
├── DatabaseSeeder.php
├── GammeSeeder.php
├── CategorieSeeder.php
├── TypeComposantSeeder.php
├── OuvrageSeeder.php
├── ComposantSeeder.php
├── CompositionOuvrageSeeder.php
├── FinitionSeeder.php
├── ComposantFinitionSeeder.php
└── CaracteristiqueSeeder.php

<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // ============================================================
        // 1. GAMMES
        // ============================================================
        $gammes = [
            [
                'nom' => 'Gamme 45',
                'slug' => 'gamme-45',
                'description' => 'Système 45mm pour menuiserie aluminium',
                'ordre_affichage' => 1,
            ],
            [
                'nom' => 'Gamme 55',
                'slug' => 'gamme-55',
                'description' => 'Système 55mm haute performance thermique',
                'ordre_affichage' => 2,
            ],
            [
                'nom' => 'Gamme Structure',
                'slug' => 'gamme-structure',
                'description' => 'Profilés pour structures porteuses et vérandas',
                'ordre_affichage' => 3,
            ],
            [
                'nom' => 'Gamme Design',
                'slug' => 'gamme-design',
                'description' => 'Profilés design pour aménagement intérieur',
                'ordre_affichage' => 4,
            ],
        ];
        foreach ($gammes as $gamme) {
            DB::table('gammes')->insert($gamme + [
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        // ============================================================
        // 2. CATÉGORIES
        // ============================================================
        $categories = [
            ['nom' => 'Fenêtre', 'slug' => 'fenetre', 'description' => 'Ouvrages de type fenêtre', 'icone' => 'window'],
            ['nom' => 'Porte', 'slug' => 'porte', 'description' => 'Ouvrages de type porte', 'icone' => 'door'],
            ['nom' => 'Véranda', 'slug' => 'veranda', 'description' => 'Ouvrages de type véranda', 'icone' => 'sun'],
            ['nom' => 'Verrière', 'slug' => 'verriere', 'description' => 'Ouvrages de type verrière', 'icone' => 'glass'],
            ['nom' => 'Garde-corps', 'slug' => 'garde-corps', 'description' => 'Ouvrages de type garde-corps', 'icone' => 'barrier'],
        ];
        foreach ($categories as $categorie) {
            DB::table('categories')->insert($categorie + [
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        // ============================================================
        // 3. TYPES DE COMPOSANT
        // ============================================================
        $typesComposant = [
            ['nom' => 'Profilé', 'slug' => 'profile', 'description' => 'Profilés aluminium extrudés'],
            ['nom' => 'Joint', 'slug' => 'joint', 'description' => 'Joints d\'étanchéité en EPDM / silicone'],
            ['nom' => 'Quincaillerie', 'slug' => 'quincaillerie', 'description' => 'Pièces de fixation et mécanismes'],
            ['nom' => 'Accessoire', 'slug' => 'accessoire', 'description' => 'Accessoires divers'],
            ['nom' => 'Vitrage', 'slug' => 'vitrage', 'description' => 'Vitrages et doubles vitrages'],
        ];
        foreach ($typesComposant as $type) {
            DB::table('types_composant')->insert($type + [
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        // ============================================================
        // 4. OUVRAGES
        // ============================================================
        $ouvrages = [
            [
                'reference' => 'FEN-001',
                'nom' => 'Fenêtre coulissante 2 vantaux',
                'slug' => 'fenetre-coulissante-2-vantaux',
                'gamme_id' => 1,
                'categorie_id' => 1,
                'description_courte' => 'Fenêtre coulissante aluminium 2 vantaux - Gamme 45',
                'description_technique' => 'Fenêtre coulissante à 2 vantaux avec système de roulement silencieux. Profilés en aluminium 6060-T6.',
                'largeur_min_mm' => 800,
                'largeur_max_mm' => 2400,
                'hauteur_min_mm' => 600,
                'hauteur_max_mm' => 2400,
                'performance_thermique' => 'Uw = 1.8 W/m²K',
                'performance_acoustique' => 'Rw = 38 dB',
                'est_actif' => true,
            ],
            [
                'reference' => 'FEN-002',
                'nom' => 'Fenêtre oscillo-battante 1 vantail',
                'slug' => 'fenetre-oscillo-battante-1-vantail',
                'gamme_id' => 2,
                'categorie_id' => 1,
                'description_courte' => 'Fenêtre oscillo-battante aluminium 1 vantail - Gamme 55',
                'description_technique' => 'Fenêtre oscillo-battante à 1 vantail avec quincaillerie de sécurité.',
                'largeur_min_mm' => 400,
                'largeur_max_mm' => 1200,
                'hauteur_min_mm' => 600,
                'hauteur_max_mm' => 2000,
                'performance_thermique' => 'Uw = 1.3 W/m²K',
                'performance_acoustique' => 'Rw = 42 dB',
                'est_actif' => true,
            ],
            [
                'reference' => 'POR-001',
                'nom' => 'Porte d\'entrée 1 vantail',
                'slug' => 'porte-entree-1-vantail',
                'gamme_id' => 1,
                'categorie_id' => 2,
                'description_courte' => 'Porte d\'entrée en aluminium 1 vantail - Gamme 45',
                'description_technique' => 'Porte d\'entrée à 1 vantail avec seuil plat et serrure multipoints.',
                'largeur_min_mm' => 700,
                'largeur_max_mm' => 1200,
                'hauteur_min_mm' => 2000,
                'hauteur_max_mm' => 2400,
                'performance_thermique' => 'Ud = 1.6 W/m²K',
                'performance_acoustique' => 'Rw = 40 dB',
                'est_actif' => true,
            ],
            [
                'reference' => 'VER-001',
                'nom' => 'Véranda 3x3 mètres',
                'slug' => 'veranda-3x3-metres',
                'gamme_id' => 3,
                'categorie_id' => 3,
                'description_courte' => 'Véranda aluminium 3x3 mètres - Gamme Structure',
                'description_technique' => 'Véranda modulaire en aluminium avec toiture en polycarbonate.',
                'largeur_min_mm' => 3000,
                'largeur_max_mm' => 3000,
                'hauteur_min_mm' => 2500,
                'hauteur_max_mm' => 2500,
                'est_actif' => true,
            ],
            [
                'reference' => 'GAR-001',
                'nom' => 'Garde-corps 1m',
                'slug' => 'garde-corps-1m',
                'gamme_id' => 3,
                'categorie_id' => 5,
                'description_courte' => 'Garde-corps aluminium 1m - Gamme Structure',
                'description_technique' => 'Garde-corps avec lisses horizontales en aluminium.',
                'largeur_min_mm' => 1000,
                'largeur_max_mm' => 3000,
                'hauteur_min_mm' => 1000,
                'hauteur_max_mm' => 1100,
                'est_actif' => true,
            ],
        ];
        foreach ($ouvrages as $ouvrage) {
            DB::table('ouvrages')->insert($ouvrage + [
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        // ============================================================
        // 5. COMPOSANTS
        // ============================================================
        $composants = [
            // Gamme 45 - Profilés
            [
                'reference' => 'PRO-001-45',
                'designation' => 'Rail haut 45mm',
                'slug' => 'rail-haut-45mm',
                'type_composant_id' => 1,
                'gamme_id' => 1,
                'matiere' => 'Alu 6060-T6',
                'longueur_barre_mm' => 6000,
                'poids_lineaire_kg_m' => 2.450,
                'section_largeur_mm' => 45.00,
                'section_hauteur_mm' => 35.00,
                'epaisseur_paroi_mm' => 1.50,
                'moment_inertie_x_cm4' => 85.30,
                'moment_inertie_y_cm4' => 42.10,
                'module_elasticite_x_cm3' => 12.40,
                'module_elasticite_y_cm3' => 8.20,
                'est_disponible' => true,
            ],
            [
                'reference' => 'PRO-002-45',
                'designation' => 'Rail bas 45mm',
                'slug' => 'rail-bas-45mm',
                'type_composant_id' => 1,
                'gamme_id' => 1,
                'matiere' => 'Alu 6060-T6',
                'longueur_barre_mm' => 6000,
                'poids_lineaire_kg_m' => 2.150,
                'section_largeur_mm' => 45.00,
                'section_hauteur_mm' => 30.00,
                'epaisseur_paroi_mm' => 1.50,
                'moment_inertie_x_cm4' => 72.10,
                'moment_inertie_y_cm4' => 38.50,
                'module_elasticite_x_cm3' => 10.80,
                'module_elasticite_y_cm3' => 7.10,
                'est_disponible' => true,
            ],
            [
                'reference' => 'PRO-003-45',
                'designation' => 'Montant 45mm',
                'slug' => 'montant-45mm',
                'type_composant_id' => 1,
                'gamme_id' => 1,
                'matiere' => 'Alu 6060-T6',
                'longueur_barre_mm' => 6000,
                'poids_lineaire_kg_m' => 1.850,
                'section_largeur_mm' => 45.00,
                'section_hauteur_mm' => 45.00,
                'epaisseur_paroi_mm' => 1.50,
                'moment_inertie_x_cm4' => 95.60,
                'moment_inertie_y_cm4' => 95.60,
                'module_elasticite_x_cm3' => 14.20,
                'module_elasticite_y_cm3' => 14.20,
                'est_disponible' => true,
            ],
            // Gamme 55 - Profilés
            [
                'reference' => 'PRO-001-55',
                'designation' => 'Rail haut 55mm',
                'slug' => 'rail-haut-55mm',
                'type_composant_id' => 1,
                'gamme_id' => 2,
                'matiere' => 'Alu 6060-T6',
                'longueur_barre_mm' => 6000,
                'poids_lineaire_kg_m' => 2.950,
                'section_largeur_mm' => 55.00,
                'section_hauteur_mm' => 40.00,
                'epaisseur_paroi_mm' => 1.80,
                'moment_inertie_x_cm4' => 125.40,
                'moment_inertie_y_cm4' => 58.30,
                'module_elasticite_x_cm3' => 18.20,
                'module_elasticite_y_cm3' => 11.40,
                'est_disponible' => true,
            ],
            [
                'reference' => 'PRO-002-55',
                'designation' => 'Rail bas 55mm',
                'slug' => 'rail-bas-55mm',
                'type_composant_id' => 1,
                'gamme_id' => 2,
                'matiere' => 'Alu 6060-T6',
                'longueur_barre_mm' => 6000,
                'poids_lineaire_kg_m' => 2.650,
                'section_largeur_mm' => 55.00,
                'section_hauteur_mm' => 35.00,
                'epaisseur_paroi_mm' => 1.80,
                'moment_inertie_x_cm4' => 108.20,
                'moment_inertie_y_cm4' => 52.10,
                'module_elasticite_x_cm3' => 16.50,
                'module_elasticite_y_cm3' => 10.20,
                'est_disponible' => true,
            ],
            // Accessoires - Joints
            [
                'reference' => 'JOINT-001',
                'designation' => 'Joint d\'étanchéité EPDM 8mm',
                'slug' => 'joint-epdm-8mm',
                'type_composant_id' => 2,
                'gamme_id' => null,
                'matiere' => 'EPDM',
                'longueur_barre_mm' => null,
                'poids_lineaire_kg_m' => 0.120,
                'section_largeur_mm' => 8.00,
                'section_hauteur_mm' => 5.00,
                'epaisseur_paroi_mm' => null,
                'est_disponible' => true,
            ],
            // Accessoires - Quincaillerie
            [
                'reference' => 'QUIN-001',
                'designation' => 'Serrure multipoints 45mm',
                'slug' => 'serrure-multipoints-45mm',
                'type_composant_id' => 3,
                'gamme_id' => 1,
                'matiere' => 'Acier zingué',
                'longueur_barre_mm' => null,
                'poids_lineaire_kg_m' => null,
                'est_disponible' => true,
            ],
            [
                'reference' => 'QUIN-002',
                'designation' => 'Roulement à billes pour coulissant',
                'slug' => 'roulement-billes-coulissant',
                'type_composant_id' => 3,
                'gamme_id' => null,
                'matiere' => 'Acier inoxydable',
                'longueur_barre_mm' => null,
                'poids_lineaire_kg_m' => null,
                'est_disponible' => true,
            ],
        ];
        foreach ($composants as $composant) {
            DB::table('composants')->insert($composant + [
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        // ============================================================
        // 6. COMPOSITION OUVRAGE
        // ============================================================
        // Fenêtre coulissante 2 vantaux (ouvrage_id = 1)
        DB::table('composition_ouvrage')->insert([
            [
                'ouvrage_id' => 1,
                'composant_id' => 1, // Rail haut 45mm
                'quantite' => 2,
                'unite' => 'u',
                'ordre' => 1,
                'longueur_coupe_mm' => 1200,
            ],
            [
                'ouvrage_id' => 1,
                'composant_id' => 2, // Rail bas 45mm
                'quantite' => 2,
                'unite' => 'u',
                'ordre' => 2,
                'longueur_coupe_mm' => 1200,
            ],
            [
                'ouvrage_id' => 1,
                'composant_id' => 3, // Montant 45mm
                'quantite' => 4,
                'unite' => 'u',
                'ordre' => 3,
                'longueur_coupe_mm' => 600,
            ],
            [
                'ouvrage_id' => 1,
                'composant_id' => 7, // Roulement à billes
                'quantite' => 4,
                'unite' => 'u',
                'ordre' => 4,
            ],
        ]);

        // Fenêtre oscillo-battante (ouvrage_id = 2)
        DB::table('composition_ouvrage')->insert([
            [
                'ouvrage_id' => 2,
                'composant_id' => 4, // Rail haut 55mm
                'quantite' => 1,
                'unite' => 'u',
                'ordre' => 1,
                'longueur_coupe_mm' => 800,
            ],
            [
                'ouvrage_id' => 2,
                'composant_id' => 5, // Rail bas 55mm
                'quantite' => 1,
                'unite' => 'u',
                'ordre' => 2,
                'longueur_coupe_mm' => 800,
            ],
            [
                'ouvrage_id' => 2,
                'composant_id' => 6, // Joint EPDM
                'quantite' => 4,
                'unite' => 'm',
                'ordre' => 3,
            ],
        ]);

        // Porte d'entrée (ouvrage_id = 3)
        DB::table('composition_ouvrage')->insert([
            [
                'ouvrage_id' => 3,
                'composant_id' => 1, // Rail haut 45mm
                'quantite' => 1,
                'unite' => 'u',
                'ordre' => 1,
                'longueur_coupe_mm' => 900,
            ],
            [
                'ouvrage_id' => 3,
                'composant_id' => 2, // Rail bas 45mm
                'quantite' => 1,
                'unite' => 'u',
                'ordre' => 2,
                'longueur_coupe_mm' => 900,
            ],
            [
                'ouvrage_id' => 3,
                'composant_id' => 3, // Montant 45mm
                'quantite' => 2,
                'unite' => 'u',
                'ordre' => 3,
                'longueur_coupe_mm' => 2000,
            ],
            [
                'ouvrage_id' => 3,
                'composant_id' => 6, // Joint EPDM
                'quantite' => 6,
                'unite' => 'm',
                'ordre' => 4,
            ],
            [
                'ouvrage_id' => 3,
                'composant_id' => 8, // Serrure multipoints
                'quantite' => 1,
                'unite' => 'u',
                'ordre' => 5,
            ],
        ]);

        // ============================================================
        // 7. FINITIONS
        // ============================================================
        $finitions = [
            ['nom' => 'Blanc RAL 9016', 'slug' => 'blanc-ral-9016', 'code_ral' => '9016', 'type_finition' => 'poudre'],
            ['nom' => 'Noir RAL 9005', 'slug' => 'noir-ral-9005', 'code_ral' => '9005', 'type_finition' => 'poudre'],
            ['nom' => 'Gris RAL 7040', 'slug' => 'gris-ral-7040', 'code_ral' => '7040', 'type_finition' => 'poudre'],
            ['nom' => 'Gris RAL 7016', 'slug' => 'gris-ral-7016', 'code_ral' => '7016', 'type_finition' => 'poudre'],
            ['nom' => 'Beige RAL 1015', 'slug' => 'beige-ral-1015', 'code_ral' => '1015', 'type_finition' => 'poudre'],
            ['nom' => 'Brun RAL 8017', 'slug' => 'brun-ral-8017', 'code_ral' => '8017', 'type_finition' => 'poudre'],
            ['nom' => 'Anodisé naturel', 'slug' => 'anodise-naturel', 'code_ral' => null, 'type_finition' => 'anodisation'],
            ['nom' => 'Anodisé bronze', 'slug' => 'anodise-bronze', 'code_ral' => null, 'type_finition' => 'anodisation'],
            ['nom' => 'Anodisé noir', 'slug' => 'anodise-noir', 'code_ral' => null, 'type_finition' => 'anodisation'],
            ['nom' => 'Brut', 'slug' => 'brut', 'code_ral' => null, 'type_finition' => 'brut'],
        ];
        foreach ($finitions as $finition) {
            DB::table('finitions')->insert($finition + [
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        // ============================================================
        // 8. COMPOSANT FINITION
        // ============================================================
        // Rail haut 45mm (composant_id = 1) → plusieurs finitions
        DB::table('composant_finition')->insert([
            ['composant_id' => 1, 'finition_id' => 1, 'est_par_defaut' => true],  // Blanc RAL 9016
            ['composant_id' => 1, 'finition_id' => 2, 'est_par_defaut' => false], // Noir RAL 9005
            ['composant_id' => 1, 'finition_id' => 3, 'est_par_defaut' => false], // Gris RAL 7040
            ['composant_id' => 1, 'finition_id' => 7, 'est_par_defaut' => false], // Anodisé naturel
        ]);

        // Rail bas 45mm (composant_id = 2)
        DB::table('composant_finition')->insert([
            ['composant_id' => 2, 'finition_id' => 1, 'est_par_defaut' => true],