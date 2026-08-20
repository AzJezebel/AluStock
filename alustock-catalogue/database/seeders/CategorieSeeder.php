<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CategorieSeeder extends Seeder
{
    public function run(): void
    {
        $categories = [
            ['nom' => 'Fenêtre', 'slug' => 'fenetre', 'description' => 'Ouvrages de type fenêtre', 'icone' => 'window'],
            ['nom' => 'Porte', 'slug' => 'porte', 'description' => 'Ouvrages de type porte', 'icone' => 'door'],
            ['nom' => 'Véranda', 'slug' => 'veranda', 'description' => 'Ouvrages de type véranda', 'icone' => 'sun'],
            ['nom' => 'Verrière', 'slug' => 'verriere', 'description' => 'Ouvrages de type verrière', 'icone' => 'glass'],
            ['nom' => 'Garde-corps', 'slug' => 'garde-corps', 'description' => 'Ouvrages de type garde-corps', 'icone' => 'barrier'],
        ];

        foreach ($categories as $categorie) {
            DB::table('categories')->insert($categorie + [
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}