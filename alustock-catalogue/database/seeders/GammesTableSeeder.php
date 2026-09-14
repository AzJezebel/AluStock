<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class GammesTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        \DB::table('gammes')->delete();
        
        \DB::table('gammes')->insert(array (
            0 => 
            array (
                'id' => 1,
                'nom' => 'Gamme 45',
                'slug' => 'gamme-45',
                'description' => 'Système 45mm pour menuiserie aluminium',
                'image_cover' => NULL,
                'ordre_affichage' => 1,
                'created_at' => '2026-09-11 21:57:20',
                'updated_at' => '2026-09-11 21:57:20',
            ),
            1 => 
            array (
                'id' => 2,
                'nom' => 'Gamme 55',
                'slug' => 'gamme-55',
                'description' => 'Système 55mm haute performance thermique',
                'image_cover' => NULL,
                'ordre_affichage' => 2,
                'created_at' => '2026-09-11 21:57:20',
                'updated_at' => '2026-09-11 21:57:20',
            ),
            2 => 
            array (
                'id' => 3,
                'nom' => 'Gamme Structure',
                'slug' => 'gamme-structure',
                'description' => 'Profilés pour structures porteuses et vérandas',
                'image_cover' => NULL,
                'ordre_affichage' => 3,
                'created_at' => '2026-09-11 21:57:20',
                'updated_at' => '2026-09-11 21:57:20',
            ),
            3 => 
            array (
                'id' => 4,
                'nom' => 'Gamme Design',
                'slug' => 'gamme-design',
                'description' => 'Profilés design pour aménagement intérieur',
                'image_cover' => NULL,
                'ordre_affichage' => 4,
                'created_at' => '2026-09-11 21:57:20',
                'updated_at' => '2026-09-11 21:57:20',
            ),
        ));
        
        
    }
}