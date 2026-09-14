<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class MediaMorphTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        \DB::table('media_morph')->delete();
        
        \DB::table('media_morph')->insert(array (
            0 => 
            array (
                'media_id' => 1,
                'mediable_type' => 'App\\Models\\Composant',
                'mediable_id' => 9,
                'ordre' => 1,
                'created_at' => '2026-09-11 21:58:16',
                'updated_at' => '2026-09-11 21:58:16',
            ),
            1 => 
            array (
                'media_id' => 2,
                'mediable_type' => 'App\\Models\\Ouvrage',
                'mediable_id' => 6,
                'ordre' => 1,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            2 => 
            array (
                'media_id' => 3,
                'mediable_type' => 'App\\Models\\Ouvrage',
                'mediable_id' => 6,
                'ordre' => 2,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            3 => 
            array (
                'media_id' => 4,
                'mediable_type' => 'App\\Models\\Composant',
                'mediable_id' => 11,
                'ordre' => 1,
                'created_at' => '2026-09-14 19:20:41',
                'updated_at' => '2026-09-14 19:20:41',
            ),
            4 => 
            array (
                'media_id' => 5,
                'mediable_type' => 'App\\Models\\Composant',
                'mediable_id' => 12,
                'ordre' => 1,
                'created_at' => '2026-09-14 19:22:21',
                'updated_at' => '2026-09-14 19:22:21',
            ),
            5 => 
            array (
                'media_id' => 6,
                'mediable_type' => 'App\\Models\\Composant',
                'mediable_id' => 13,
                'ordre' => 1,
                'created_at' => '2026-09-14 19:23:21',
                'updated_at' => '2026-09-14 19:23:21',
            ),
            6 => 
            array (
                'media_id' => 7,
                'mediable_type' => 'App\\Models\\Composant',
                'mediable_id' => 14,
                'ordre' => 1,
                'created_at' => '2026-09-14 19:35:27',
                'updated_at' => '2026-09-14 19:35:27',
            ),
            7 => 
            array (
                'media_id' => 8,
                'mediable_type' => 'App\\Models\\Composant',
                'mediable_id' => 15,
                'ordre' => 1,
                'created_at' => '2026-09-14 19:36:19',
                'updated_at' => '2026-09-14 19:36:19',
            ),
            8 => 
            array (
                'media_id' => 9,
                'mediable_type' => 'App\\Models\\Composant',
                'mediable_id' => 16,
                'ordre' => 1,
                'created_at' => '2026-09-14 19:37:10',
                'updated_at' => '2026-09-14 19:37:10',
            ),
            9 => 
            array (
                'media_id' => 10,
                'mediable_type' => 'App\\Models\\Composant',
                'mediable_id' => 17,
                'ordre' => 1,
                'created_at' => '2026-09-14 19:38:27',
                'updated_at' => '2026-09-14 19:38:27',
            ),
            10 => 
            array (
                'media_id' => 11,
                'mediable_type' => 'App\\Models\\Composant',
                'mediable_id' => 18,
                'ordre' => 1,
                'created_at' => '2026-09-14 19:39:07',
                'updated_at' => '2026-09-14 19:39:07',
            ),
            11 => 
            array (
                'media_id' => 12,
                'mediable_type' => 'App\\Models\\Composant',
                'mediable_id' => 19,
                'ordre' => 1,
                'created_at' => '2026-09-14 19:39:48',
                'updated_at' => '2026-09-14 19:39:48',
            ),
            12 => 
            array (
                'media_id' => 13,
                'mediable_type' => 'App\\Models\\Composant',
                'mediable_id' => 20,
                'ordre' => 1,
                'created_at' => '2026-09-14 19:40:41',
                'updated_at' => '2026-09-14 19:40:41',
            ),
            13 => 
            array (
                'media_id' => 14,
                'mediable_type' => 'App\\Models\\Composant',
                'mediable_id' => 21,
                'ordre' => 1,
                'created_at' => '2026-09-14 19:41:20',
                'updated_at' => '2026-09-14 19:41:20',
            ),
            14 => 
            array (
                'media_id' => 15,
                'mediable_type' => 'App\\Models\\Composant',
                'mediable_id' => 22,
                'ordre' => 1,
                'created_at' => '2026-09-14 19:41:52',
                'updated_at' => '2026-09-14 19:41:52',
            ),
        ));
        
        
    }
}