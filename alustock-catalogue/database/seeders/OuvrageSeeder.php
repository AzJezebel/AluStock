<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class OuvrageSeeder extends Seeder
{
    public function run(): void
    {
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
    }
}