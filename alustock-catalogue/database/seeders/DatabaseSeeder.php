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
        // $this->call([
        //     // 1. Tables indépendantes
        //     GammeSeeder::class,
        //     CategorieSeeder::class,
        //     TypeComposantSeeder::class,

        //     // 2. Tables dépendantes (FK)
        //     OuvrageSeeder::class,
        //     ComposantSeeder::class,

        //     // 3. Tables de liaison
        //     CompositionOuvrageSeeder::class,
        //     FinitionSeeder::class,
        //     ComposantFinitionSeeder::class,

        //     // 4. Caractéristiques (EAV)
        //     CaracteristiqueSeeder::class,
        // ]);

        //php artisan iseed gammes,categories,types_composant,ouvrages,composants,composition_ouvrage,finitions,composant_finition,caracteristiques,medias,media_morph --force

        $this->call(GammesTableSeeder::class);
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