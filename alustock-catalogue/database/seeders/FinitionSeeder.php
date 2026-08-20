<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class FinitionSeeder extends Seeder
{
    public function run(): void
    {
        $finitions = [
            ['nom' => 'Blanc RAL 9016', 'slug' => 'blanc-ral-9016', 'code_ral' => '9016', 'type_finition' => 'poudre'],
            ['nom' => 'Noir RAL 9005', 'slug' => 'noir-ral-9005', 'code_ral' => '9005', 'type_finition' => 'poudre'],
            ['nom' => 'Gris RAL 7040', 'slug' => 'gris-ral-7040', 'code_ral' => '7040', 'type_finition' => 'poudre'],
            ['nom' => 'Gris RAL 7016', 'slug' => 'gris-ral-7016', 'code_ral' => '7016', 'type_finition' => 'poudre'],
            ['nom' => 'Beige RAL 1015', 'slug' => 'beige-ral-1015', 'code_ral' => '1015', 'type_finition' => 'poudre'],
            ['nom' => 'Brun RAL 8017', 'slug' => 'brun-ral-8017', 'code_ral' => '8017', 'type_finition' => 'poudre'],
            ['nom' => 'Anodisé naturel', 'slug' => 'anodise-naturel', 'code_ral' => null, 'type_finition' => 'anodisation'],
            ['nom' => 'Anodisé bronze', 'slug' => 'anodise-bronze', 'code_ral' => null, 'type_finition' => 'anodisation'],
            ['nom' => 'Anodisé noir', 'slug' => 'anodise-noir', 'code_ral' => null, 'type_finition' => 'anodisation'],
            ['nom' => 'Brut', 'slug' => 'brut', 'code_ral' => null, 'type_finition' => 'brut'],
        ];

        foreach ($finitions as $finition) {
            DB::table('finitions')->insert($finition + [
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}