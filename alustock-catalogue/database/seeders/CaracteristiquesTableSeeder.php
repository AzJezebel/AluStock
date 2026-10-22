<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class CaracteristiquesTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        \DB::table('caracteristiques')->delete();
        
        \DB::table('caracteristiques')->insert(array (
            0 => 
            array (
                'id' => 15,
                'caracterisable_type' => 'ouvrage',
                'caracterisable_id' => 33,
                'cle' => 'joint',
                'valeur' => 'Joint de battue',
                'unite' => '1',
                'ordre_affichage' => 1,
                'created_at' => '2026-09-24 19:48:13',
                'updated_at' => '2026-09-24 19:48:13',
            ),
            1 => 
            array (
                'id' => 16,
                'caracterisable_type' => 'ouvrage',
                'caracterisable_id' => 33,
                'cle' => 'joint',
                'valeur' => 'Joint a bourrer  6mm 27-237L',
                'unite' => '2',
                'ordre_affichage' => 2,
                'created_at' => '2026-09-24 19:48:43',
                'updated_at' => '2026-09-24 19:48:43',
            ),
            2 => 
            array (
                'id' => 17,
                'caracterisable_type' => 'ouvrage',
                'caracterisable_id' => 34,
                'cle' => 'joint',
                'valeur' => 'Joint a bourrer 6mm 27-237L',
                'unite' => '2',
                'ordre_affichage' => 1,
                'created_at' => '2026-09-24 19:55:00',
                'updated_at' => '2026-09-24 19:55:00',
            ),
            3 => 
            array (
                'id' => 18,
                'caracterisable_type' => 'ouvrage',
                'caracterisable_id' => 34,
                'cle' => 'joint',
                'valeur' => 'Joint a clipper 6mm 27-962L',
                'unite' => '2',
                'ordre_affichage' => 2,
                'created_at' => '2026-09-24 19:55:00',
                'updated_at' => '2026-09-24 19:55:00',
            ),
            4 => 
            array (
                'id' => 19,
                'caracterisable_type' => 'ouvrage',
                'caracterisable_id' => 34,
                'cle' => 'joint',
                'valeur' => 'Joint bas de porte 27-312L',
                'unite' => '1',
                'ordre_affichage' => 3,
                'created_at' => '2026-09-24 19:55:00',
                'updated_at' => '2026-09-24 19:55:00',
            ),
            5 => 
            array (
                'id' => 20,
                'caracterisable_type' => 'ouvrage',
                'caracterisable_id' => 35,
                'cle' => 'joint',
                'valeur' => 'Joint de battue',
                'unite' => '1',
                'ordre_affichage' => 1,
                'created_at' => '2026-09-24 19:57:09',
                'updated_at' => '2026-09-24 19:57:09',
            ),
            6 => 
            array (
                'id' => 21,
                'caracterisable_type' => 'ouvrage',
                'caracterisable_id' => 35,
                'cle' => 'joint',
                'valeur' => 'Joint barriere',
                'unite' => '2',
                'ordre_affichage' => 2,
                'created_at' => '2026-09-24 19:57:26',
                'updated_at' => '2026-09-24 19:57:26',
            ),
            7 => 
            array (
                'id' => 22,
                'caracterisable_type' => 'ouvrage',
                'caracterisable_id' => 35,
                'cle' => 'joint',
                'valeur' => 'Joint a bourrer  6mm 27-237L',
                'unite' => '2',
                'ordre_affichage' => 3,
                'created_at' => '2026-09-24 19:58:07',
                'updated_at' => '2026-09-24 19:58:07',
            ),
            8 => 
            array (
                'id' => 23,
                'caracterisable_type' => 'ouvrage',
                'caracterisable_id' => 35,
                'cle' => 'joint',
                'valeur' => 'Joint a clipper 6mm 27-962L',
                'unite' => '2',
                'ordre_affichage' => 4,
                'created_at' => '2026-09-24 19:58:27',
                'updated_at' => '2026-09-24 19:58:27',
            ),
            9 => 
            array (
                'id' => 24,
                'caracterisable_type' => 'ouvrage',
                'caracterisable_id' => 36,
                'cle' => 'joint',
                'valeur' => 'Joint barriere',
                'unite' => '2',
                'ordre_affichage' => 1,
                'created_at' => '2026-09-24 20:00:54',
                'updated_at' => '2026-09-24 20:00:54',
            ),
            10 => 
            array (
                'id' => 25,
                'caracterisable_type' => 'ouvrage',
                'caracterisable_id' => 36,
                'cle' => 'joint',
                'valeur' => 'Joint a bourrer 6mm 27-237L',
                'unite' => '2',
                'ordre_affichage' => 2,
                'created_at' => '2026-09-24 20:00:54',
                'updated_at' => '2026-09-24 20:00:54',
            ),
            11 => 
            array (
                'id' => 26,
                'caracterisable_type' => 'ouvrage',
                'caracterisable_id' => 36,
                'cle' => 'joint',
                'valeur' => 'Joint a clipper 27-962L',
                'unite' => '2',
                'ordre_affichage' => 3,
                'created_at' => '2026-09-24 20:00:54',
                'updated_at' => '2026-09-24 20:00:54',
            ),
            12 => 
            array (
                'id' => 27,
                'caracterisable_type' => 'ouvrage',
                'caracterisable_id' => 37,
                'cle' => 'joint',
                'valeur' => 'Joint de battue',
                'unite' => '2',
                'ordre_affichage' => 1,
                'created_at' => '2026-09-24 20:04:01',
                'updated_at' => '2026-09-24 20:04:01',
            ),
            13 => 
            array (
                'id' => 28,
                'caracterisable_type' => 'ouvrage',
                'caracterisable_id' => 37,
                'cle' => 'joint',
                'valeur' => 'Joint de battue',
                'unite' => '1',
                'ordre_affichage' => 2,
                'created_at' => '2026-09-24 20:04:01',
                'updated_at' => '2026-09-24 20:04:01',
            ),
            14 => 
            array (
                'id' => 29,
                'caracterisable_type' => 'ouvrage',
                'caracterisable_id' => 37,
                'cle' => 'joint',
                'valeur' => 'Joint a bourrer 6mm 27-237L',
                'unite' => '4',
                'ordre_affichage' => 3,
                'created_at' => '2026-09-24 20:04:01',
                'updated_at' => '2026-09-24 20:04:01',
            ),
            15 => 
            array (
                'id' => 30,
                'caracterisable_type' => 'ouvrage',
                'caracterisable_id' => 37,
                'cle' => 'joint',
                'valeur' => 'Joint a clipper 6mm 27-962L',
                'unite' => '2',
                'ordre_affichage' => 4,
                'created_at' => '2026-09-24 20:04:01',
                'updated_at' => '2026-09-24 20:04:01',
            ),
        ));
        
        
    }
}