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
        //php artisan iseed categories,types_composant,ouvrages,composants,composition_ouvrage,finitions,composant_finition,caracteristiques,medias,media_morph --force

        $this->call(CategoriesTableSeeder::class);
        $this->call(TypesComposantTableSeeder::class);
        $this->call(OuvragesTableSeeder::class);
        $this->call(ComposantsTableSeeder::class);
        $this->call(CompositionOuvrageTableSeeder::class);
        $this->call(FinitionsTableSeeder::class);
        $this->call(ComposantFinitionTableSeeder::class);
        $this->call(CaracteristiquesTableSeeder::class);
        $this->call(MediasTableSeeder::class);
        $this->call(MediaMorphTableSeeder::class);
    }
}