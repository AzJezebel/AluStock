<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class CategoriesTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        \DB::table('categories')->delete();
        
        \DB::table('categories')->insert(array (
            0 => 
            array (
                'id' => 1,
                'nom' => 'Fenêtre',
                'slug' => 'fenetre',
                'description' => 'Ouvrages de type fenêtre',
                'image' => 'images/categories/fenetre.png',
                'created_at' => '2026-09-11 21:57:20',
                'updated_at' => '2026-09-11 21:57:20',
            ),
            1 => 
            array (
                'id' => 2,
                'nom' => 'Porte',
                'slug' => 'porte',
                'description' => 'Ouvrages de type porte',
                'image' => 'images/categories/porte.png',
                'created_at' => '2026-09-11 21:57:20',
                'updated_at' => '2026-09-11 21:57:20',
            ),
            2 => 
            array (
                'id' => 3,
                'nom' => 'Véranda',
                'slug' => 'veranda',
                'description' => 'Ouvrages de type véranda',
                'image' => 'images/categories/veranda.png',
                'created_at' => '2026-09-11 21:57:20',
                'updated_at' => '2026-09-11 21:57:20',
            ),
            3 => 
            array (
                'id' => 4,
                'nom' => 'Verrière',
                'slug' => 'verriere',
                'description' => 'Ouvrages de type verrière',
                'image' => 'images/categories/verriere.png',
                'created_at' => '2026-09-11 21:57:20',
                'updated_at' => '2026-09-11 21:57:20',
            ),
            4 => 
            array (
                'id' => 5,
                'nom' => 'Garde-corps',
                'slug' => 'garde-corps',
                'description' => 'Ouvrages de type garde-corps',
                'image' => 'images/categories/garde-corps.png',
                'created_at' => '2026-09-11 21:57:20',
                'updated_at' => '2026-09-11 21:57:20',
            ),
        ));
        
        
    }
}