<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class CaracteristiquesTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        \DB::table('caracteristiques')->delete();
        
        \DB::table('caracteristiques')->insert(array (
            0 => 
            array (
                'id' => 1,
                'caracterisable_type' => 'App\\Models\\Composant',
                'caracterisable_id' => 1,
                'cle' => 'diametre_de_roue',
                'valeur' => '52',
                'unite' => 'mm',
                'ordre_affichage' => 1,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            1 => 
            array (
                'id' => 2,
                'caracterisable_type' => 'App\\Models\\Composant',
                'caracterisable_id' => 1,
                'cle' => 'nombre_de_gorges',
                'valeur' => '2',
                'unite' => NULL,
                'ordre_affichage' => 2,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            2 => 
            array (
                'id' => 3,
                'caracterisable_type' => 'App\\Models\\Composant',
                'caracterisable_id' => 1,
                'cle' => 'rayon_de_courbure_min',
                'valeur' => '250',
                'unite' => 'mm',
                'ordre_affichage' => 3,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            3 => 
            array (
                'id' => 4,
                'caracterisable_type' => 'App\\Models\\Composant',
                'caracterisable_id' => 1,
                'cle' => 'temperature_max_utilisation',
                'valeur' => '80',
                'unite' => '°C',
                'ordre_affichage' => 4,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            4 => 
            array (
                'id' => 5,
                'caracterisable_type' => 'App\\Models\\Composant',
                'caracterisable_id' => 4,
                'cle' => 'diametre_de_roue',
                'valeur' => '62',
                'unite' => 'mm',
                'ordre_affichage' => 1,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            5 => 
            array (
                'id' => 6,
                'caracterisable_type' => 'App\\Models\\Composant',
                'caracterisable_id' => 4,
                'cle' => 'nombre_de_gorges',
                'valeur' => '3',
                'unite' => NULL,
                'ordre_affichage' => 2,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            6 => 
            array (
                'id' => 7,
                'caracterisable_type' => 'App\\Models\\Composant',
                'caracterisable_id' => 4,
                'cle' => 'rayon_de_courbure_min',
                'valeur' => '300',
                'unite' => 'mm',
                'ordre_affichage' => 3,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            7 => 
            array (
                'id' => 8,
                'caracterisable_type' => 'App\\Models\\Ouvrage',
                'caracterisable_id' => 1,
                'cle' => 'type_ouverture',
                'valeur' => 'coulissante_2_vantaux',
                'unite' => NULL,
                'ordre_affichage' => 1,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            8 => 
            array (
                'id' => 9,
                'caracterisable_type' => 'App\\Models\\Ouvrage',
                'caracterisable_id' => 1,
                'cle' => 'nombre_de_vantaux',
                'valeur' => '2',
                'unite' => NULL,
                'ordre_affichage' => 2,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            9 => 
            array (
                'id' => 10,
                'caracterisable_type' => 'App\\Models\\Ouvrage',
                'caracterisable_id' => 1,
                'cle' => 'type_de_fermeture',
                'valeur' => 'cremone_avec_poignee',
                'unite' => NULL,
                'ordre_affichage' => 3,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            10 => 
            array (
                'id' => 11,
                'caracterisable_type' => 'App\\Models\\Ouvrage',
                'caracterisable_id' => 3,
                'cle' => 'type_ouverture',
                'valeur' => 'battante_1_vantail',
                'unite' => NULL,
                'ordre_affichage' => 1,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            11 => 
            array (
                'id' => 12,
                'caracterisable_type' => 'App\\Models\\Ouvrage',
                'caracterisable_id' => 3,
                'cle' => 'sens_ouverture',
                'valeur' => 'interieur_ou_exterieur',
                'unite' => NULL,
                'ordre_affichage' => 2,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            12 => 
            array (
                'id' => 13,
                'caracterisable_type' => 'App\\Models\\Ouvrage',
                'caracterisable_id' => 3,
                'cle' => 'classe_de_resistance',
                'valeur' => 'RC2',
                'unite' => NULL,
                'ordre_affichage' => 3,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            13 => 
            array (
                'id' => 14,
                'caracterisable_type' => 'App\\Models\\Ouvrage',
                'caracterisable_id' => 6,
                'cle' => 'Couleur',
                'valeur' => 'Idkk',
                'unite' => '',
                'ordre_affichage' => 1,
                'created_at' => '2026-09-11 21:59:20',
                'updated_at' => '2026-09-11 21:59:20',
            ),
        ));
        
        
    }
}