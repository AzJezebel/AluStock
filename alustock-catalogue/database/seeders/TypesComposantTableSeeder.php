<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class TypesComposantTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        \DB::table('types_composant')->delete();
        
        \DB::table('types_composant')->insert(array (
            0 => 
            array (
                'id' => 1,
                'nom' => 'Profilé',
                'slug' => 'profile',
                'description' => 'Profilés aluminium extrudés',
                'created_at' => '2026-09-11 21:57:20',
                'updated_at' => '2026-09-11 21:57:20',
            ),
            1 => 
            array (
                'id' => 2,
                'nom' => 'Joint',
                'slug' => 'joint',
                'description' => 'Joints d\'étanchéité en EPDM / silicone',
                'created_at' => '2026-09-11 21:57:20',
                'updated_at' => '2026-09-11 21:57:20',
            ),
            2 => 
            array (
                'id' => 3,
                'nom' => 'Quincaillerie',
                'slug' => 'quincaillerie',
                'description' => 'Pièces de fixation et mécanismes',
                'created_at' => '2026-09-11 21:57:20',
                'updated_at' => '2026-09-11 21:57:20',
            ),
            3 => 
            array (
                'id' => 4,
                'nom' => 'Accessoire',
                'slug' => 'accessoire',
                'description' => 'Accessoires divers',
                'created_at' => '2026-09-11 21:57:20',
                'updated_at' => '2026-09-11 21:57:20',
            ),
            4 => 
            array (
                'id' => 5,
                'nom' => 'Vitrage',
                'slug' => 'vitrage',
                'description' => 'Vitrages et doubles vitrages',
                'created_at' => '2026-09-11 21:57:20',
                'updated_at' => '2026-09-11 21:57:20',
            ),
        ));
        
        
    }
}