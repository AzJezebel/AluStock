<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ComposantFinitionSeeder extends Seeder
{
    public function run(): void
    {
        // Rail haut 45mm (composant_id = 1) → plusieurs finitions
        DB::table('composant_finition')->insert([
            ['composant_id' => 1, 'finition_id' => 1, 'est_par_defaut' => true],  // Blanc RAL 9016
            ['composant_id' => 1, 'finition_id' => 2, 'est_par_defaut' => false], // Noir RAL 9005
            ['composant_id' => 1, 'finition_id' => 3, 'est_par_defaut' => false], // Gris RAL 7040
            ['composant_id' => 1, 'finition_id' => 7, 'est_par_defaut' => false], // Anodisé naturel
        ]);

        // Rail bas 45mm (composant_id = 2)
        DB::table('composant_finition')->insert([
            ['composant_id' => 2, 'finition_id' => 1, 'est_par_defaut' => true],
            ['composant_id' => 2, 'finition_id' => 2, 'est_par_defaut' => false],
            ['composant_id' => 2, 'finition_id' => 3, 'est_par_defaut' => false],
            ['composant_id' => 2, 'finition_id' => 7, 'est_par_defaut' => false],
        ]);

        // Montant 45mm (composant_id = 3)
        DB::table('composant_finition')->insert([
            ['composant_id' => 3, 'finition_id' => 1, 'est_par_defaut' => true],
            ['composant_id' => 3, 'finition_id' => 2, 'est_par_defaut' => false],
            ['composant_id' => 3, 'finition_id' => 4, 'est_par_defaut' => false],
            ['composant_id' => 3, 'finition_id' => 7, 'est_par_defaut' => false],
        ]);
    }
}