<?php
// database/seeders/OuvrageSeeder.php

namespace Database\Seeders;

use App\Models\Ouvrage;
use App\Models\Categorie;
use App\Models\Gamme;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class OuvrageSeeder extends Seeder
{
    public function run(): void
    {
        $ouvrages = [
            [
                'titre' => 'Fenêtre panoramique aluminium',
                'description' => 'Fenêtre coulissante avec profilés gamme 45, isolation thermique renforcée et double vitrage.',
                'reference' => 'WIN-2024-001',
                'categorie_nom' => 'Fenêtre',
                'gamme_nom' => 'Gamme 45',
                'date_realisation' => '2024-01-15',
                'client' => 'Résidence Les Jardins',
                'localisation' => 'Paris, France',
                'is_featured' => true,
                'is_active' => true,
            ],
            [
                'titre' => 'Porte d\'entrée design',
                'description' => 'Porte en aluminium avec profilés gamme design, finition brossée et serrure multipoints.',
                'reference' => 'DOOR-2024-002',
                'categorie_nom' => 'Porte',
                'gamme_nom' => 'Gamme Design',
                'date_realisation' => '2024-02-20',
                'client' => 'Villa Moderne',
                'localisation' => 'Lyon, France',
                'is_featured' => true,
                'is_active' => true,
            ],
            [
                'titre' => 'Véranda bioclimatique',
                'description' => 'Véranda en aluminium avec toiture vitrée et stores intégrés. Profilés gamme 55.',
                'reference' => 'VER-2024-003',
                'categorie_nom' => 'Véranda',
                'gamme_nom' => 'Gamme 55',
                'date_realisation' => '2024-03-10',
                'client' => 'Maison de campagne',
                'localisation' => 'Bordeaux, France',
                'is_featured' => true,
                'is_active' => true,
            ],
            [
                'titre' => 'Verrière industrielle',
                'description' => 'Verrière sur mesure pour loft industriel. Structure en profilés gamme structure.',
                'reference' => 'SKY-2024-004',
                'categorie_nom' => 'Verrière',
                'gamme_nom' => 'Gamme Structure',
                'date_realisation' => '2024-04-05',
                'client' => 'Loft Saint-Martin',
                'localisation' => 'Marseille, France',
                'is_featured' => true,
                'is_active' => true,
            ],
            [
                'titre' => 'Garde-corps moderne',
                'description' => 'Garde-corps en verre et aluminium pour terrasse, finition noir mat.',
                'reference' => 'RAIL-2024-005',
                'categorie_nom' => 'Garde-corps',
                'gamme_nom' => 'Gamme Design',
                'date_realisation' => '2024-05-12',
                'client' => 'Appartement Vue Mer',
                'localisation' => 'Nice, France',
                'is_featured' => false,
                'is_active' => true,
            ],
            [
                'titre' => 'Fenêtre à galandage',
                'description' => 'Fenêtre à galandage en aluminium, système de coulisse haut de gamme.',
                'reference' => 'WIN-2024-006',
                'categorie_nom' => 'Fenêtre',
                'gamme_nom' => 'Gamme 45',
                'date_realisation' => '2024-06-18',
                'client' => 'Centre commercial',
                'localisation' => 'Strasbourg, France',
                'is_featured' => true,
                'is_active' => true,
            ],
        ];

        foreach ($ouvrages as $ouvrageData) {
            $categorie = Categorie::where('nom', $ouvrageData['categorie_nom'])->first();
            $gamme = Gamme::where('nom', $ouvrageData['gamme_nom'])->first();

            if ($categorie && $gamme) {
                Ouvrage::create([
                    'titre' => $ouvrageData['titre'],
                    'slug' => Str::slug($ouvrageData['titre']),
                    'description' => $ouvrageData['description'],
                    'reference' => $ouvrageData['reference'],
                    'categorie_id' => $categorie->id,
                    'gamme_id' => $gamme->id,
                    'date_realisation' => $ouvrageData['date_realisation'],
                    'client' => $ouvrageData['client'],
                    'localisation' => $ouvrageData['localisation'],
                    'is_featured' => $ouvrageData['is_featured'],
                    'is_active' => $ouvrageData['is_active'],
                ]);
            }
        }
    }
}