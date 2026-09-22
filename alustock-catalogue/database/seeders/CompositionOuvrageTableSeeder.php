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
            2 => 
            array (
                'ouvrage_id' => 7,
                'composant_id' => 17,
                'quantite' => '1.00',
                'unite' => 'u',
                'ordre' => 5,
                'commentaire' => NULL,
                'created_at' => '2026-09-22 20:49:56',
                'updated_at' => '2026-09-22 20:49:56',
            ),
            3 => 
            array (
                'ouvrage_id' => 7,
                'composant_id' => 18,
                'quantite' => '1.00',
                'unite' => 'u',
                'ordre' => 4,
                'commentaire' => NULL,
                'created_at' => '2026-09-22 20:44:10',
                'updated_at' => '2026-09-22 20:44:10',
            ),
            4 => 
            array (
                'ouvrage_id' => 7,
                'composant_id' => 34,
                'quantite' => '1.00',
                'unite' => 'u',
                'ordre' => 2,
                'commentaire' => NULL,
                'created_at' => '2026-09-15 19:37:41',
                'updated_at' => '2026-09-15 19:37:41',
            ),
            5 => 
            array (
                'ouvrage_id' => 7,
                'composant_id' => 35,
                'quantite' => '1.00',
                'unite' => 'u',
                'ordre' => 3,
                'commentaire' => NULL,
                'created_at' => '2026-09-15 19:38:03',
                'updated_at' => '2026-09-15 19:38:03',
            ),
            6 => 
            array (
                'ouvrage_id' => 8,
                'composant_id' => 17,
                'quantite' => '1.00',
                'unite' => 'u',
                'ordre' => 4,
                'commentaire' => NULL,
                'created_at' => '2026-09-22 20:51:25',
                'updated_at' => '2026-09-22 20:51:25',
            ),
            7 => 
            array (
                'ouvrage_id' => 8,
                'composant_id' => 18,
                'quantite' => '1.00',
                'unite' => 'u',
                'ordre' => 3,
                'commentaire' => NULL,
                'created_at' => '2026-09-22 20:44:29',
                'updated_at' => '2026-09-22 20:44:29',
            ),
            8 => 
            array (
                'ouvrage_id' => 8,
                'composant_id' => 34,
                'quantite' => '1.00',
                'unite' => 'u',
                'ordre' => 2,
                'commentaire' => NULL,
                'created_at' => '2026-09-15 19:41:39',
                'updated_at' => '2026-09-15 19:41:39',
            ),
            9 => 
            array (
                'ouvrage_id' => 9,
                'composant_id' => 16,
                'quantite' => '1.00',
                'unite' => 'u',
                'ordre' => 4,
                'commentaire' => NULL,
                'created_at' => '2026-09-15 20:40:43',
                'updated_at' => '2026-09-15 20:40:43',
            ),
            10 => 
            array (
                'ouvrage_id' => 9,
                'composant_id' => 37,
                'quantite' => '1.00',
                'unite' => 'u',
                'ordre' => 1,
                'commentaire' => NULL,
                'created_at' => '2026-09-15 20:39:56',
                'updated_at' => '2026-09-15 20:39:56',
            ),
            11 => 
            array (
                'ouvrage_id' => 9,
                'composant_id' => 38,
                'quantite' => '1.00',
                'unite' => 'u',
                'ordre' => 2,
                'commentaire' => NULL,
                'created_at' => '2026-09-15 20:40:06',
                'updated_at' => '2026-09-15 20:40:06',
            ),
            12 => 
            array (
                'ouvrage_id' => 9,
                'composant_id' => 40,
                'quantite' => '1.00',
                'unite' => 'u',
                'ordre' => 3,
                'commentaire' => NULL,
                'created_at' => '2026-09-15 20:40:16',
                'updated_at' => '2026-09-15 20:40:16',
            ),
            13 => 
            array (
                'ouvrage_id' => 9,
                'composant_id' => 41,
                'quantite' => '1.00',
                'unite' => 'u',
                'ordre' => 5,
                'commentaire' => NULL,
                'created_at' => '2026-09-15 20:41:01',
                'updated_at' => '2026-09-15 20:41:01',
            ),
            14 => 
            array (
                'ouvrage_id' => 9,
                'composant_id' => 42,
                'quantite' => '1.00',
                'unite' => 'u',
                'ordre' => 6,
                'commentaire' => NULL,
                'created_at' => '2026-09-15 20:41:12',
                'updated_at' => '2026-09-15 20:41:12',
            ),
            15 => 
            array (
                'ouvrage_id' => 10,
                'composant_id' => 16,
                'quantite' => '1.00',
                'unite' => 'u',
                'ordre' => 6,
                'commentaire' => NULL,
                'created_at' => '2026-09-15 20:59:59',
                'updated_at' => '2026-09-15 20:59:59',
            ),
            16 => 
            array (
                'ouvrage_id' => 10,
                'composant_id' => 17,
                'quantite' => '1.00',
                'unite' => 'u',
                'ordre' => 7,
                'commentaire' => NULL,
                'created_at' => '2026-09-15 21:00:08',
                'updated_at' => '2026-09-15 21:00:08',
            ),
            17 => 
            array (
                'ouvrage_id' => 10,
                'composant_id' => 18,
                'quantite' => '1.00',
                'unite' => 'u',
                'ordre' => 12,
                'commentaire' => NULL,
                'created_at' => '2026-09-22 20:44:45',
                'updated_at' => '2026-09-22 20:44:45',
            ),
            18 => 
            array (
                'ouvrage_id' => 10,
                'composant_id' => 37,
                'quantite' => '1.00',
                'unite' => 'u',
                'ordre' => 9,
                'commentaire' => NULL,
                'created_at' => '2026-09-15 21:00:36',
                'updated_at' => '2026-09-15 21:00:36',
            ),
            19 => 
            array (
                'ouvrage_id' => 10,
                'composant_id' => 39,
                'quantite' => '1.00',
                'unite' => 'u',
                'ordre' => 8,
                'commentaire' => NULL,
                'created_at' => '2026-09-15 21:00:23',
                'updated_at' => '2026-09-15 21:00:23',
            ),
            20 => 
            array (
                'ouvrage_id' => 10,
                'composant_id' => 41,
                'quantite' => '1.00',
                'unite' => 'u',
                'ordre' => 4,
                'commentaire' => NULL,
                'created_at' => '2026-09-15 20:59:17',
                'updated_at' => '2026-09-15 20:59:17',
            ),
            21 => 
            array (
                'ouvrage_id' => 10,
                'composant_id' => 42,
                'quantite' => '1.00',
                'unite' => 'u',
                'ordre' => 11,
                'commentaire' => NULL,
                'created_at' => '2026-09-15 21:35:16',
                'updated_at' => '2026-09-15 21:35:16',
            ),
            22 => 
            array (
                'ouvrage_id' => 10,
                'composant_id' => 43,
                'quantite' => '1.00',
                'unite' => 'u',
                'ordre' => 1,
                'commentaire' => NULL,
                'created_at' => '2026-09-15 20:48:40',
                'updated_at' => '2026-09-15 20:48:40',
            ),
            23 => 
            array (
                'ouvrage_id' => 10,
                'composant_id' => 44,
                'quantite' => '1.00',
                'unite' => 'u',
                'ordre' => 2,
                'commentaire' => NULL,
                'created_at' => '2026-09-15 20:57:59',
                'updated_at' => '2026-09-15 20:57:59',
            ),
            24 => 
            array (
                'ouvrage_id' => 10,
                'composant_id' => 45,
                'quantite' => '1.00',
                'unite' => 'u',
                'ordre' => 3,
                'commentaire' => NULL,
                'created_at' => '2026-09-15 20:58:14',
                'updated_at' => '2026-09-15 20:58:14',
            ),
            25 => 
            array (
                'ouvrage_id' => 10,
                'composant_id' => 46,
                'quantite' => '1.00',
                'unite' => 'u',
                'ordre' => 10,
                'commentaire' => NULL,
                'created_at' => '2026-09-15 21:00:44',
                'updated_at' => '2026-09-15 21:00:44',
            ),
            26 => 
            array (
                'ouvrage_id' => 12,
                'composant_id' => 16,
                'quantite' => '1.00',
                'unite' => 'u',
                'ordre' => 3,
                'commentaire' => NULL,
                'created_at' => '2026-09-15 21:37:10',
                'updated_at' => '2026-09-15 21:37:10',
            ),
            27 => 
            array (
                'ouvrage_id' => 12,
                'composant_id' => 38,
                'quantite' => '1.00',
                'unite' => 'u',
                'ordre' => 5,
                'commentaire' => NULL,
                'created_at' => '2026-09-15 21:37:10',
                'updated_at' => '2026-09-15 21:37:10',
            ),
            28 => 
            array (
                'ouvrage_id' => 12,
                'composant_id' => 40,
                'quantite' => '1.00',
                'unite' => 'u',
                'ordre' => 4,
                'commentaire' => NULL,
                'created_at' => '2026-09-15 21:37:10',
                'updated_at' => '2026-09-15 21:37:10',
            ),
            29 => 
            array (
                'ouvrage_id' => 12,
                'composant_id' => 41,
                'quantite' => '1.00',
                'unite' => 'u',
                'ordre' => 2,
                'commentaire' => NULL,
                'created_at' => '2026-09-15 21:37:10',
                'updated_at' => '2026-09-15 21:37:10',
            ),
            30 => 
            array (
                'ouvrage_id' => 12,
                'composant_id' => 42,
                'quantite' => '1.00',
                'unite' => 'u',
                'ordre' => 1,
                'commentaire' => NULL,
                'created_at' => '2026-09-15 21:37:10',
                'updated_at' => '2026-09-15 21:37:10',
            ),
            31 => 
            array (
                'ouvrage_id' => 12,
                'composant_id' => 43,
                'quantite' => '1.00',
                'unite' => 'u',
                'ordre' => 6,
                'commentaire' => NULL,
                'created_at' => '2026-09-15 21:37:10',
                'updated_at' => '2026-09-15 21:37:10',
            ),
            32 => 
            array (
                'ouvrage_id' => 12,
                'composant_id' => 47,
                'quantite' => '1.00',
                'unite' => 'u',
                'ordre' => 7,
                'commentaire' => NULL,
                'created_at' => '2026-09-15 21:38:01',
                'updated_at' => '2026-09-15 21:38:01',
            ),
            33 => 
            array (
                'ouvrage_id' => 13,
                'composant_id' => 53,
                'quantite' => '1.00',
                'unite' => 'u',
                'ordre' => 1,
                'commentaire' => NULL,
                'created_at' => '2026-09-18 18:45:51',
                'updated_at' => '2026-09-18 18:45:51',
            ),
            34 => 
            array (
                'ouvrage_id' => 13,
                'composant_id' => 54,
                'quantite' => '1.00',
                'unite' => 'u',
                'ordre' => 3,
                'commentaire' => NULL,
                'created_at' => '2026-09-18 18:47:14',
                'updated_at' => '2026-09-18 18:47:14',
            ),
            35 => 
            array (
                'ouvrage_id' => 13,
                'composant_id' => 55,
                'quantite' => '1.00',
                'unite' => 'u',
                'ordre' => 2,
                'commentaire' => NULL,
                'created_at' => '2026-09-18 18:47:08',
                'updated_at' => '2026-09-18 18:47:08',
            ),
            36 => 
            array (
                'ouvrage_id' => 13,
                'composant_id' => 56,
                'quantite' => '1.00',
                'unite' => 'u',
                'ordre' => 4,
                'commentaire' => NULL,
                'created_at' => '2026-09-18 18:47:19',
                'updated_at' => '2026-09-18 18:47:19',
            ),
            37 => 
            array (
                'ouvrage_id' => 14,
                'composant_id' => 63,
                'quantite' => '1.00',
                'unite' => 'u',
                'ordre' => 1,
                'commentaire' => NULL,
                'created_at' => '2026-09-21 17:15:14',
                'updated_at' => '2026-09-21 17:15:14',
            ),
            38 => 
            array (
                'ouvrage_id' => 14,
                'composant_id' => 71,
                'quantite' => '1.00',
                'unite' => 'u',
                'ordre' => 2,
                'commentaire' => NULL,
                'created_at' => '2026-09-21 17:15:14',
                'updated_at' => '2026-09-21 17:15:14',
            ),
            39 => 
            array (
                'ouvrage_id' => 14,
                'composant_id' => 72,
                'quantite' => '1.00',
                'unite' => 'u',
                'ordre' => 3,
                'commentaire' => NULL,
                'created_at' => '2026-09-21 17:15:14',
                'updated_at' => '2026-09-21 17:15:14',
            ),
            40 => 
            array (
                'ouvrage_id' => 15,
                'composant_id' => 11,
                'quantite' => '1.00',
                'unite' => 'u',
                'ordre' => 1,
                'commentaire' => NULL,
                'created_at' => '2026-09-22 20:35:58',
                'updated_at' => '2026-09-22 20:35:58',
            ),
            41 => 
            array (
                'ouvrage_id' => 15,
                'composant_id' => 17,
                'quantite' => '1.00',
                'unite' => 'u',
                'ordre' => 3,
                'commentaire' => NULL,
                'created_at' => '2026-09-22 20:52:17',
                'updated_at' => '2026-09-22 20:52:17',
            ),
            42 => 
            array (
                'ouvrage_id' => 15,
                'composant_id' => 18,
                'quantite' => '1.00',
                'unite' => 'u',
                'ordre' => 2,
                'commentaire' => NULL,
                'created_at' => '2026-09-22 20:35:58',
                'updated_at' => '2026-09-22 20:35:58',
            ),
        ));
        
        
    }
}