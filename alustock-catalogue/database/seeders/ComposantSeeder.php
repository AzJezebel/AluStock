<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ComposantSeeder extends Seeder
{
    public function run(): void
    {
        $composants = [
            // Gamme 45 - Profilés
            [
                'reference' => 'PRO-001-45',
                'designation' => 'Rail haut 45mm',
                'slug' => 'rail-haut-45mm',
                'type_composant_id' => 1,
                'gamme_id' => 1,
                'matiere' => 'Alu 6060-T6',
                'longueur_barre_mm' => 6000,
                'poids_lineaire_kg_m' => 2.450,
                'section_largeur_mm' => 45.00,
                'section_hauteur_mm' => 35.00,
                'epaisseur_paroi_mm' => 1.50,
                'moment_inertie_x_cm4' => 85.30,
                'moment_inertie_y_cm4' => 42.10,
                'module_elasticite_x_cm3' => 12.40,
                'module_elasticite_y_cm3' => 8.20,
                'est_disponible' => true,
            ],
            [
                'reference' => 'PRO-002-45',
                'designation' => 'Rail bas 45mm',
                'slug' => 'rail-bas-45mm',
                'type_composant_id' => 1,
                'gamme_id' => 1,
                'matiere' => 'Alu 6060-T6',
                'longueur_barre_mm' => 6000,
                'poids_lineaire_kg_m' => 2.150,
                'section_largeur_mm' => 45.00,
                'section_hauteur_mm' => 30.00,
                'epaisseur_paroi_mm' => 1.50,
                'moment_inertie_x_cm4' => 72.10,
                'moment_inertie_y_cm4' => 38.50,
                'module_elasticite_x_cm3' => 10.80,
                'module_elasticite_y_cm3' => 7.10,
                'est_disponible' => true,
            ],
            [
                'reference' => 'PRO-003-45',
                'designation' => 'Montant 45mm',
                'slug' => 'montant-45mm',
                'type_composant_id' => 1,
                'gamme_id' => 1,
                'matiere' => 'Alu 6060-T6',
                'longueur_barre_mm' => 6000,
                'poids_lineaire_kg_m' => 1.850,
                'section_largeur_mm' => 45.00,
                'section_hauteur_mm' => 45.00,
                'epaisseur_paroi_mm' => 1.50,
                'moment_inertie_x_cm4' => 95.60,
                'moment_inertie_y_cm4' => 95.60,
                'module_elasticite_x_cm3' => 14.20,
                'module_elasticite_y_cm3' => 14.20,
                'est_disponible' => true,
            ],
            // Gamme 55 - Profilés
            [
                'reference' => 'PRO-001-55',
                'designation' => 'Rail haut 55mm',
                'slug' => 'rail-haut-55mm',
                'type_composant_id' => 1,
                'gamme_id' => 2,
                'matiere' => 'Alu 6060-T6',
                'longueur_barre_mm' => 6000,
                'poids_lineaire_kg_m' => 2.950,
                'section_largeur_mm' => 55.00,
                'section_hauteur_mm' => 40.00,
                'epaisseur_paroi_mm' => 1.80,
                'moment_inertie_x_cm4' => 125.40,
                'moment_inertie_y_cm4' => 58.30,
                'module_elasticite_x_cm3' => 18.20,
                'module_elasticite_y_cm3' => 11.40,
                'est_disponible' => true,
            ],
            [
                'reference' => 'PRO-002-55',
                'designation' => 'Rail bas 55mm',
                'slug' => 'rail-bas-55mm',
                'type_composant_id' => 1,
                'gamme_id' => 2,
                'matiere' => 'Alu 6060-T6',
                'longueur_barre_mm' => 6000,
                'poids_lineaire_kg_m' => 2.650,
                'section_largeur_mm' => 55.00,
                'section_hauteur_mm' => 35.00,
                'epaisseur_paroi_mm' => 1.80,
                'moment_inertie_x_cm4' => 108.20,
                'moment_inertie_y_cm4' => 52.10,
                'module_elasticite_x_cm3' => 16.50,
                'module_elasticite_y_cm3' => 10.20,
                'est_disponible' => true,
            ],
            // Accessoires - Joints
            [
                'reference' => 'JOINT-001',
                'designation' => 'Joint d\'étanchéité EPDM 8mm',
                'slug' => 'joint-epdm-8mm',
                'type_composant_id' => 2,
                'gamme_id' => null,
                'matiere' => 'EPDM',
                'longueur_barre_mm' => null,
                'poids_lineaire_kg_m' => 0.120,
                'section_largeur_mm' => 8.00,
                'section_hauteur_mm' => 5.00,
                'epaisseur_paroi_mm' => null,
                'est_disponible' => true,
            ],
            // Accessoires - Quincaillerie
            [
                'reference' => 'QUIN-001',
                'designation' => 'Serrure multipoints 45mm',
                'slug' => 'serrure-multipoints-45mm',
                'type_composant_id' => 3,
                'gamme_id' => 1,
                'matiere' => 'Acier zingué',
                'longueur_barre_mm' => null,
                'poids_lineaire_kg_m' => null,
                'est_disponible' => true,
            ],
            [
                'reference' => 'QUIN-002',
                'designation' => 'Roulement à billes pour coulissant',
                'slug' => 'roulement-billes-coulissant',
                'type_composant_id' => 3,
                'gamme_id' => null,
                'matiere' => 'Acier inoxydable',
                'longueur_barre_mm' => null,
                'poids_lineaire_kg_m' => null,
                'est_disponible' => true,
            ],
        ];

        foreach ($composants as $composant) {
            DB::table('composants')->insert($composant + [
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}