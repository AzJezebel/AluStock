<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class OuvragesTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        \DB::table('ouvrages')->delete();
        
        \DB::table('ouvrages')->insert(array (
            0 => 
            array (
                'id' => 1,
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
                'image_principale' => NULL,
                'est_actif' => 1,
                'created_at' => '2026-09-11 21:57:20',
                'updated_at' => '2026-09-11 21:57:20',
            ),
            1 => 
            array (
                'id' => 6,
                'reference' => 'PORTE-TEST-123',
                'nom' => 'PORTE-TEST',
                'slug' => 'porte-test',
                'gamme_id' => NULL,
                'categorie_id' => 2,
                'description_courte' => 'garwgarwegawergaw',
                'description_technique' => 'fawgfawgaw',
                'largeur_min_mm' => NULL,
                'largeur_max_mm' => NULL,
                'hauteur_min_mm' => NULL,
                'hauteur_max_mm' => NULL,
                'performance_thermique' => NULL,
                'performance_acoustique' => NULL,
                'image_principale' => NULL,
                'est_actif' => 1,
                'created_at' => '2026-09-11 21:59:20',
                'updated_at' => '2026-09-11 21:59:20',
            ),
            2 => 
            array (
                'id' => 7,
                'reference' => 'BAR 009',
                'nom' => 'MODULO SAN ANDRES',
                'slug' => 'modulo-san-andres',
                'gamme_id' => NULL,
                'categorie_id' => 2,
                'description_courte' => NULL,
                'description_technique' => NULL,
                'largeur_min_mm' => NULL,
                'largeur_max_mm' => NULL,
                'hauteur_min_mm' => NULL,
                'hauteur_max_mm' => NULL,
                'performance_thermique' => NULL,
                'performance_acoustique' => NULL,
                'image_principale' => NULL,
                'est_actif' => 1,
                'created_at' => '2026-09-15 19:06:18',
                'updated_at' => '2026-09-15 19:06:18',
            ),
        ));
        
        
    }
}