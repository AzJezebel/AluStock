<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class GammeSeeder extends Seeder
{
    public function run(): void
    {
        $gammes = [
            [
                'nom' => 'Gamme 45',
                'slug' => 'gamme-45',
                'description' => 'Système 45mm pour menuiserie aluminium',
                'ordre_affichage' => 1,
            ],
            [
                'nom' => 'Gamme 55',
                'slug' => 'gamme-55',
                'description' => 'Système 55mm haute performance thermique',
                'ordre_affichage' => 2,
            ],
            [
                'nom' => 'Gamme Structure',
                'slug' => 'gamme-structure',
                'description' => 'Profilés pour structures porteuses et vérandas',
                'ordre_affichage' => 3,
            ],
            [
                'nom' => 'Gamme Design',
                'slug' => 'gamme-design',
                'description' => 'Profilés design pour aménagement intérieur',
                'ordre_affichage' => 4,
            ],
        ];

        foreach ($gammes as $gamme) {
            DB::table('gammes')->insert($gamme + [
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}