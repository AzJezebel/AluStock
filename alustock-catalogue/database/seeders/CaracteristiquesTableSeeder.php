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
            14 => 
            array (
                'id' => 15,
                'caracterisable_type' => 'App\\Models\\Ouvrage',
                'caracterisable_id' => 33,
                'cle' => 'Joint',
                'valeur' => 'Joint de battue',
                'unite' => '1',
                'ordre_affichage' => 1,
                'created_at' => '2026-09-24 19:48:13',
                'updated_at' => '2026-09-24 19:48:13',
            ),
            15 => 
            array (
                'id' => 16,
                'caracterisable_type' => 'App\\Models\\Ouvrage',
                'caracterisable_id' => 33,
                'cle' => 'Joint',
                'valeur' => 'Joint a bourrer  6mm 27-237L',
                'unite' => '2',
                'ordre_affichage' => 2,
                'created_at' => '2026-09-24 19:48:43',
                'updated_at' => '2026-09-24 19:48:43',
            ),
            16 => 
            array (
                'id' => 17,
                'caracterisable_type' => 'App\\Models\\Ouvrage',
                'caracterisable_id' => 34,
                'cle' => 'Joint',
                'valeur' => 'Joint a bourrer 6mm 27-237L',
                'unite' => '2',
                'ordre_affichage' => 1,
                'created_at' => '2026-09-24 19:55:00',
                'updated_at' => '2026-09-24 19:55:00',
            ),
            17 => 
            array (
                'id' => 18,
                'caracterisable_type' => 'App\\Models\\Ouvrage',
                'caracterisable_id' => 34,
                'cle' => 'Joint',
                'valeur' => 'Joint a clipper 6mm 27-962L',
                'unite' => '2',
                'ordre_affichage' => 2,
                'created_at' => '2026-09-24 19:55:00',
                'updated_at' => '2026-09-24 19:55:00',
            ),
            18 => 
            array (
                'id' => 19,
                'caracterisable_type' => 'App\\Models\\Ouvrage',
                'caracterisable_id' => 34,
                'cle' => 'Joint',
                'valeur' => 'Joint bas de porte 27-312L',
                'unite' => '1',
                'ordre_affichage' => 3,
                'created_at' => '2026-09-24 19:55:00',
                'updated_at' => '2026-09-24 19:55:00',
            ),
            19 => 
            array (
                'id' => 20,
                'caracterisable_type' => 'App\\Models\\Ouvrage',
                'caracterisable_id' => 35,
                'cle' => 'Joint',
                'valeur' => 'Joint de battue',
                'unite' => '1',
                'ordre_affichage' => 1,
                'created_at' => '2026-09-24 19:57:09',
                'updated_at' => '2026-09-24 19:57:09',
            ),
            20 => 
            array (
                'id' => 21,
                'caracterisable_type' => 'App\\Models\\Ouvrage',
                'caracterisable_id' => 35,
                'cle' => 'Joint',
                'valeur' => 'Joint barriere',
                'unite' => '2',
                'ordre_affichage' => 2,
                'created_at' => '2026-09-24 19:57:26',
                'updated_at' => '2026-09-24 19:57:26',
            ),
            21 => 
            array (
                'id' => 22,
                'caracterisable_type' => 'App\\Models\\Ouvrage',
                'caracterisable_id' => 35,
                'cle' => 'Joint',
                'valeur' => 'Joint a bourrer  6mm 27-237L',
                'unite' => '2',
                'ordre_affichage' => 3,
                'created_at' => '2026-09-24 19:58:07',
                'updated_at' => '2026-09-24 19:58:07',
            ),
            22 => 
            array (
                'id' => 23,
                'caracterisable_type' => 'App\\Models\\Ouvrage',
                'caracterisable_id' => 35,
                'cle' => 'Joint',
                'valeur' => 'Joint a clipper 6mm 27-962L',
                'unite' => '2',
                'ordre_affichage' => 4,
                'created_at' => '2026-09-24 19:58:27',
                'updated_at' => '2026-09-24 19:58:27',
            ),
            23 => 
            array (
                'id' => 24,
                'caracterisable_type' => 'App\\Models\\Ouvrage',
                'caracterisable_id' => 36,
                'cle' => 'Joint',
                'valeur' => 'Joint barriere',
                'unite' => '2',
                'ordre_affichage' => 1,
                'created_at' => '2026-09-24 20:00:54',
                'updated_at' => '2026-09-24 20:00:54',
            ),
            24 => 
            array (
                'id' => 25,
                'caracterisable_type' => 'App\\Models\\Ouvrage',
                'caracterisable_id' => 36,
                'cle' => 'Joint',
                'valeur' => 'Joint a bourrer 6mm 27-237L',
                'unite' => '2',
                'ordre_affichage' => 2,
                'created_at' => '2026-09-24 20:00:54',
                'updated_at' => '2026-09-24 20:00:54',
            ),
            25 => 
            array (
                'id' => 26,
                'caracterisable_type' => 'App\\Models\\Ouvrage',
                'caracterisable_id' => 36,
                'cle' => 'Joint',
                'valeur' => 'Joint a clipper 27-962L',
                'unite' => '2',
                'ordre_affichage' => 3,
                'created_at' => '2026-09-24 20:00:54',
                'updated_at' => '2026-09-24 20:00:54',
            ),
            26 => 
            array (
                'id' => 27,
                'caracterisable_type' => 'App\\Models\\Ouvrage',
                'caracterisable_id' => 37,
                'cle' => 'Joint',
                'valeur' => 'Joint de battue',
                'unite' => '2',
                'ordre_affichage' => 1,
                'created_at' => '2026-09-24 20:04:01',
                'updated_at' => '2026-09-24 20:04:01',
            ),
            27 => 
            array (
                'id' => 28,
                'caracterisable_type' => 'App\\Models\\Ouvrage',
                'caracterisable_id' => 37,
                'cle' => 'Joint',
                'valeur' => 'Joint de battue',
                'unite' => '1',
                'ordre_affichage' => 2,
                'created_at' => '2026-09-24 20:04:01',
                'updated_at' => '2026-09-24 20:04:01',
            ),
            28 => 
            array (
                'id' => 29,
                'caracterisable_type' => 'App\\Models\\Ouvrage',
                'caracterisable_id' => 37,
                'cle' => 'Joint',
                'valeur' => 'Joint a bourrer 6mm 27-237L',
                'unite' => '4',
                'ordre_affichage' => 3,
                'created_at' => '2026-09-24 20:04:01',
                'updated_at' => '2026-09-24 20:04:01',
            ),
            29 => 
            array (
                'id' => 30,
                'caracterisable_type' => 'App\\Models\\Ouvrage',
                'caracterisable_id' => 37,
                'cle' => 'Joint',
                'valeur' => 'Joint a clipper 6mm 27-962L',
                'unite' => '2',
                'ordre_affichage' => 4,
                'created_at' => '2026-09-24 20:04:01',
                'updated_at' => '2026-09-24 20:04:01',
            ),
        ));
        
        
    }
}