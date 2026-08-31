<?php
// database/seeders/CategorieSeeder.php

namespace Database\Seeders;

use App\Models\Categorie;
use Illuminate\Database\Seeder;

class CategorieSeeder extends Seeder
{
    public function run(): void
    {
        $categories = [
            ['nom' => 'Fenêtre', 'description' => 'Fenêtres et châssis en aluminium', 'icone' => 'fa-window-maximize', 'ordre' => 1],
            ['nom' => 'Porte', 'description' => 'Portes d\'entrée et intérieures', 'icone' => 'fa-door-open', 'ordre' => 2],
            ['nom' => 'Véranda', 'description' => 'Vérandas et extensions', 'icone' => 'fa-house-chimney', 'ordre' => 3],
            ['nom' => 'Verrière', 'description' => 'Verrières et puits de lumière', 'icone' => 'fa-sun', 'ordre' => 4],
            ['nom' => 'Garde-corps', 'description' => 'Garde-corps et rambardes', 'icone' => 'fa-shield', 'ordre' => 5],
        ];

        foreach ($categories as $categorie) {
            Categorie::create(array_merge($categorie, [
                'slug' => \Illuminate\Support\Str::slug($categorie['nom'])
            ]));
        }
    }
}