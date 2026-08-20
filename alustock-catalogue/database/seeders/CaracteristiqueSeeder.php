<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CaracteristiqueSeeder extends Seeder
{
    public function run(): void
    {
        // ============================================================
        // Caractéristiques pour les COMPOSANTS
        // ============================================================

        // Caractéristiques pour Rail haut 45mm (composant_id = 1)
        DB::table('caracteristiques')->insert([
            [
                'caracterisable_type' => 'App\Models\Composant',
                'caracterisable_id' => 1,
                'cle' => 'diametre_de_roue',
                'valeur' => '52',
                'unite' => 'mm',
                'ordre_affichage' => 1,
            ],
            [
                'caracterisable_type' => 'App\Models\Composant',
                'caracterisable_id' => 1,
                'cle' => 'nombre_de_gorges',
                'valeur' => '2',
                'unite' => null,
                'ordre_affichage' => 2,
            ],
            [
                'caracterisable_type' => 'App\Models\Composant',
                'caracterisable_id' => 1,
                'cle' => 'rayon_de_courbure_min',
                'valeur' => '250',
                'unite' => 'mm',
                'ordre_affichage' => 3,
            ],
            [
                'caracterisable_type' => 'App\Models\Composant',
                'caracterisable_id' => 1,
                'cle' => 'temperature_max_utilisation',
                'valeur' => '80',
                'unite' => '°C',
                'ordre_affichage' => 4,
            ],
        ]);

        // Caractéristiques pour Rail haut 55mm (composant_id = 4)
        DB::table('caracteristiques')->insert([
            [
                'caracterisable_type' => 'App\Models\Composant',
                'caracterisable_id' => 4,
                'cle' => 'diametre_de_roue',
                'valeur' => '62',
                'unite' => 'mm',
                'ordre_affichage' => 1,
            ],
            [
                'caracterisable_type' => 'App\Models\Composant',
                'caracterisable_id' => 4,
                'cle' => 'nombre_de_gorges',
                'valeur' => '3',
                'unite' => null,
                'ordre_affichage' => 2,
            ],
            [
                'caracterisable_type' => 'App\Models\Composant',
                'caracterisable_id' => 4,
                'cle' => 'rayon_de_courbure_min',
                'valeur' => '300',
                'unite' => 'mm',
                'ordre_affichage' => 3,
            ],
        ]);

        // ============================================================
        // Caractéristiques pour les OUVRAGES
        // ============================================================

        // Caractéristiques pour Fenêtre coulissante 2 vantaux (ouvrage_id = 1)
        DB::table('caracteristiques')->insert([
            [
                'caracterisable_type' => 'App\Models\Ouvrage',
                'caracterisable_id' => 1,
                'cle' => 'type_ouverture',
                'valeur' => 'coulissante_2_vantaux',
                'unite' => null,
                'ordre_affichage' => 1,
            ],
            [
                'caracterisable_type' => 'App\Models\Ouvrage',
                'caracterisable_id' => 1,
                'cle' => 'nombre_de_vantaux',
                'valeur' => '2',
                'unite' => null,
                'ordre_affichage' => 2,
            ],
            [
                'caracterisable_type' => 'App\Models\Ouvrage',
                'caracterisable_id' => 1,
                'cle' => 'type_de_fermeture',
                'valeur' => 'cremone_avec_poignee',
                'unite' => null,
                'ordre_affichage' => 3,
            ],
        ]);

        // Caractéristiques pour Porte d'entrée (ouvrage_id = 3)
        DB::table('caracteristiques')->insert([
            [
                'caracterisable_type' => 'App\Models\Ouvrage',
                'caracterisable_id' => 3,
                'cle' => 'type_ouverture',
                'valeur' => 'battante_1_vantail',
                'unite' => null,
                'ordre_affichage' => 1,
            ],
            [
                'caracterisable_type' => 'App\Models\Ouvrage',
                'caracterisable_id' => 3,
                'cle' => 'sens_ouverture',
                'valeur' => 'interieur_ou_exterieur',
                'unite' => null,
                'ordre_affichage' => 2,
            ],
            [
                'caracterisable_type' => 'App\Models\Ouvrage',
                'caracterisable_id' => 3,
                'cle' => 'classe_de_resistance',
                'valeur' => 'RC2',
                'unite' => null,
                'ordre_affichage' => 3,
            ],
        ]);
    }
}