<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class TypeComposantSeeder extends Seeder
{
    public function run(): void
    {
        $typesComposant = [
            ['nom' => 'Profilé', 'slug' => 'profile', 'description' => 'Profilés aluminium extrudés'],
            ['nom' => 'Joint', 'slug' => 'joint', 'description' => 'Joints d\'étanchéité en EPDM / silicone'],
            ['nom' => 'Quincaillerie', 'slug' => 'quincaillerie', 'description' => 'Pièces de fixation et mécanismes'],
            ['nom' => 'Accessoire', 'slug' => 'accessoire', 'description' => 'Accessoires divers'],
            ['nom' => 'Vitrage', 'slug' => 'vitrage', 'description' => 'Vitrages et doubles vitrages'],
        ];

        foreach ($typesComposant as $type) {
            DB::table('types_composant')->insert($type + [
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}