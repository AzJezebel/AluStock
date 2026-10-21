<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CategorieSeeder extends Seeder
{
    public function run(): void
    {
        $categories = [
            ['nom' => 'Fenêtre', 'slug' => 'fenetre', 'description' => 'Ouvrages de type fenêtre', 'image' => 'images/categories/fenetre.png'],
            ['nom' => 'Porte', 'slug' => 'porte', 'description' => 'Ouvrages de type porte', 'image' => 'images/categories/porte.png'],
            ['nom' => 'Véranda', 'slug' => 'veranda', 'description' => 'Ouvrages de type véranda', 'image' => 'images/categories/veranda.png'],
            ['nom' => 'Verrière', 'slug' => 'verriere', 'description' => 'Ouvrages de type verrière', 'image' => 'images/categories/verriere.png'],
            ['nom' => 'Garde-corps', 'slug' => 'garde-corps', 'description' => 'Ouvrages de type garde-corps', 'image' => 'images/categories/garde-corps.png'],
        ];

        foreach ($categories as $categorie) {
            DB::table('categories')->insert($categorie + [
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}