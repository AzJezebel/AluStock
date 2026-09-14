<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class CompositionOuvrageTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        \DB::table('composition_ouvrage')->delete();
        
        \DB::table('composition_ouvrage')->insert(array (
            0 => 
            array (
                'ouvrage_id' => 1,
                'composant_id' => 17,
                'quantite' => '1.00',
                'unite' => 'u',
                'ordre' => 2,
                'commentaire' => NULL,
                'created_at' => '2026-09-14 19:43:13',
                'updated_at' => '2026-09-14 19:43:13',
            ),
            1 => 
            array (
                'ouvrage_id' => 1,
                'composant_id' => 22,
                'quantite' => '1.00',
                'unite' => 'u',
                'ordre' => 1,
                'commentaire' => NULL,
                'created_at' => '2026-09-14 19:43:08',
                'updated_at' => '2026-09-14 19:43:08',
            ),
        ));
        
        
    }
}