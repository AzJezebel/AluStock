<?php
// database/seeders/GammeSeeder.php

namespace Database\Seeders;

use App\Models\Gamme;
use Illuminate\Database\Seeder;

class GammeSeeder extends Seeder
{
    public function run(): void
    {
        $gammes = [
            ['nom' => 'Gamme 45', 'description' => 'Profilés 45mm pour structures légères', 'icone' => 'fa-cube', 'couleur' => '#4A90D9', 'ordre' => 1],
            ['nom' => 'Gamme 55', 'description' => 'Profilés 55mm pour structures lourdes', 'icone' => 'fa-cubes', 'couleur' => '#2ECC71', 'ordre' => 2],
            ['nom' => 'Gamme Structure', 'description' => 'Profilés structurels renforcés', 'icone' => 'fa-building', 'couleur' => '#E67E22', 'ordre' => 3],
            ['nom' => 'Gamme Design', 'description' => 'Profilés design et architecturaux', 'icone' => 'fa-paint-brush', 'couleur' => '#9B59B6', 'ordre' => 4],
        ];

        foreach ($gammes as $gamme) {
            Gamme::create(array_merge($gamme, [
                'slug' => \Illuminate\Support\Str::slug($gamme['nom'])
            ]));
        }
    }
}