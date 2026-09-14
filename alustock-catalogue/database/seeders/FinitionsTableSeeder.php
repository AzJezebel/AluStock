<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class FinitionsTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        \DB::table('finitions')->delete();
        
        \DB::table('finitions')->insert(array (
            0 => 
            array (
                'id' => 1,
                'nom' => 'Blanc RAL 9016',
                'slug' => 'blanc-ral-9016',
                'code_ral' => '9016',
                'type_finition' => 'poudre',
                'description' => NULL,
                'created_at' => '2026-09-11 21:57:20',
                'updated_at' => '2026-09-11 21:57:20',
            ),
            1 => 
            array (
                'id' => 2,
                'nom' => 'Noir RAL 9005',
                'slug' => 'noir-ral-9005',
                'code_ral' => '9005',
                'type_finition' => 'poudre',
                'description' => NULL,
                'created_at' => '2026-09-11 21:57:20',
                'updated_at' => '2026-09-11 21:57:20',
            ),
            2 => 
            array (
                'id' => 3,
                'nom' => 'Gris RAL 7040',
                'slug' => 'gris-ral-7040',
                'code_ral' => '7040',
                'type_finition' => 'poudre',
                'description' => NULL,
                'created_at' => '2026-09-11 21:57:20',
                'updated_at' => '2026-09-11 21:57:20',
            ),
            3 => 
            array (
                'id' => 4,
                'nom' => 'Gris RAL 7016',
                'slug' => 'gris-ral-7016',
                'code_ral' => '7016',
                'type_finition' => 'poudre',
                'description' => NULL,
                'created_at' => '2026-09-11 21:57:20',
                'updated_at' => '2026-09-11 21:57:20',
            ),
            4 => 
            array (
                'id' => 5,
                'nom' => 'Beige RAL 1015',
                'slug' => 'beige-ral-1015',
                'code_ral' => '1015',
                'type_finition' => 'poudre',
                'description' => NULL,
                'created_at' => '2026-09-11 21:57:20',
                'updated_at' => '2026-09-11 21:57:20',
            ),
            5 => 
            array (
                'id' => 6,
                'nom' => 'Brun RAL 8017',
                'slug' => 'brun-ral-8017',
                'code_ral' => '8017',
                'type_finition' => 'poudre',
                'description' => NULL,
                'created_at' => '2026-09-11 21:57:20',
                'updated_at' => '2026-09-11 21:57:20',
            ),
            6 => 
            array (
                'id' => 7,
                'nom' => 'Anodisé naturel',
                'slug' => 'anodise-naturel',
                'code_ral' => NULL,
                'type_finition' => 'anodisation',
                'description' => NULL,
                'created_at' => '2026-09-11 21:57:20',
                'updated_at' => '2026-09-11 21:57:20',
            ),
            7 => 
            array (
                'id' => 8,
                'nom' => 'Anodisé bronze',
                'slug' => 'anodise-bronze',
                'code_ral' => NULL,
                'type_finition' => 'anodisation',
                'description' => NULL,
                'created_at' => '2026-09-11 21:57:20',
                'updated_at' => '2026-09-11 21:57:20',
            ),
            8 => 
            array (
                'id' => 9,
                'nom' => 'Anodisé noir',
                'slug' => 'anodise-noir',
                'code_ral' => NULL,
                'type_finition' => 'anodisation',
                'description' => NULL,
                'created_at' => '2026-09-11 21:57:20',
                'updated_at' => '2026-09-11 21:57:20',
            ),
            9 => 
            array (
                'id' => 10,
                'nom' => 'Brut',
                'slug' => 'brut',
                'code_ral' => NULL,
                'type_finition' => 'brut',
                'description' => NULL,
                'created_at' => '2026-09-11 21:57:20',
                'updated_at' => '2026-09-11 21:57:20',
            ),
        ));
        
        
    }
}