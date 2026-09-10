<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CompositionOuvrageSeeder extends Seeder
{
    public function run(): void
    {
        // Ordre des colonnes : ouvrage_id, composant_id, quantite, unite, ordre, longueur_coupe_mm
        DB::table('composition_ouvrage')->insert([
            // Fenêtre coulissante 2 vantaux (ouvrage_id = 1)
            [
                'ouvrage_id' => 1,
                'composant_id' => 1,  // Rail haut 45mm
                'quantite' => 2,
                'unite' => 'u',
                'ordre' => 1,
            ],
            [
                'ouvrage_id' => 1,
                'composant_id' => 2,  // Rail bas 45mm
                'quantite' => 2,
                'unite' => 'u',
                'ordre' => 2,
            ],
            [
                'ouvrage_id' => 1,
                'composant_id' => 3,  // Montant 45mm
                'quantite' => 4,
                'unite' => 'u',
                'ordre' => 3,
            ],
            [
                'ouvrage_id' => 1,
                'composant_id' => 8,  // Roulement à billes
                'quantite' => 4,
                'unite' => 'u',
                'ordre' => 4,
            ],

            // Fenêtre oscillo-battante (ouvrage_id = 2)
            [
                'ouvrage_id' => 2,
                'composant_id' => 4,  // Rail haut 55mm
                'quantite' => 1,
                'unite' => 'u',
                'ordre' => 1,
            ],
            [
                'ouvrage_id' => 2,
                'composant_id' => 5,  // Rail bas 55mm
                'quantite' => 1,
                'unite' => 'u',
                'ordre' => 2,
            ],
            [
                'ouvrage_id' => 2,
                'composant_id' => 6,  // Joint EPDM
                'quantite' => 4,
                'unite' => 'm',
                'ordre' => 3,
            ],

            // Porte d'entrée (ouvrage_id = 3)
            [
                'ouvrage_id' => 3,
                'composant_id' => 1,  // Rail haut 45mm
                'quantite' => 1,
                'unite' => 'u',
                'ordre' => 1,
            ],
            [
                'ouvrage_id' => 3,
                'composant_id' => 2,  // Rail bas 45mm
                'quantite' => 1,
                'unite' => 'u',
                'ordre' => 2,
            ],
            [
                'ouvrage_id' => 3,
                'composant_id' => 3,  // Montant 45mm
                'quantite' => 2,
                'unite' => 'u',
                'ordre' => 3,
            ],
            [
                'ouvrage_id' => 3,
                'composant_id' => 6,  // Joint EPDM
                'quantite' => 6,
                'unite' => 'm',
                'ordre' => 4,
            ],
            [
                'ouvrage_id' => 3,
                'composant_id' => 7,  // Serrure multipoints
                'quantite' => 1,
                'unite' => 'u',
                'ordre' => 5,
            ],
        ]);
    }
}