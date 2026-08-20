<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            // 1. Tables indépendantes
            GammeSeeder::class,
            CategorieSeeder::class,
            TypeComposantSeeder::class,

            // 2. Tables dépendantes (FK)
            OuvrageSeeder::class,
            ComposantSeeder::class,

            // 3. Tables de liaison
            CompositionOuvrageSeeder::class,
            FinitionSeeder::class,
            ComposantFinitionSeeder::class,

            // 4. Caractéristiques (EAV)
            CaracteristiqueSeeder::class,
        ]);
    }
}