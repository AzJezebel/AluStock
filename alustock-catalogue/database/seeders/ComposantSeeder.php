<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ComposantSeeder extends Seeder
{
    public function run(): void
    {
        $composants = [
            // ============================================================
            // GAMME 45 — PROFILÉS
            // ============================================================
            [
                'reference' => 'PRO-001-45',
                'designation' => 'Rail haut 45mm',
                'slug' => 'rail-haut-45mm',
                'type_composant_id' => 1, // Profilé
                'gamme_id' => 1,          // Gamme 45
                'matiere' => 'Alu 6060-T6',
                'longueur_barre_mm' => 6000,
                'section_largeur_mm' => 45.00,
                'section_hauteur_mm' => 35.00,
                'epaisseur_paroi_mm' => 1.50,
                'poids_lineaire_kg_m' => 2.450,
                'poids_lineaire_lbs_ft' => 1.647,
                'moment_inertie_cm4' => 85.30,
                'perimetre_mm' => 205.39,
                'image_coupe' => null,
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
                'section_largeur_mm' => 45.00,
                'section_hauteur_mm' => 30.00,
                'epaisseur_paroi_mm' => 1.50,
                'poids_lineaire_kg_m' => 2.150,
                'poids_lineaire_lbs_ft' => 1.445,
                'moment_inertie_cm4' => 72.10,
                'perimetre_mm' => 150.01,
                'image_coupe' => null,
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
                'section_largeur_mm' => 45.00,
                'section_hauteur_mm' => 45.00,
                'epaisseur_paroi_mm' => 1.50,
                'poids_lineaire_kg_m' => 1.850,
                'poids_lineaire_lbs_ft' => 1.243,
                'moment_inertie_cm4' => 95.60,
                'perimetre_mm' => 191.86,
                'image_coupe' => null,
                'est_disponible' => true,
            ],

            // ============================================================
            // GAMME 55 — PROFILÉS
            // ============================================================
            [
                'reference' => 'PRO-001-55',
                'designation' => 'Rail haut 55mm',
                'slug' => 'rail-haut-55mm',
                'type_composant_id' => 1,
                'gamme_id' => 2,          // Gamme 55
                'matiere' => 'Alu 6060-T6',
                'longueur_barre_mm' => 6000,
                'section_largeur_mm' => 55.00,
                'section_hauteur_mm' => 40.00,
                'epaisseur_paroi_mm' => 1.80,
                'poids_lineaire_kg_m' => 2.950,
                'poids_lineaire_lbs_ft' => 1.982,
                'moment_inertie_cm4' => 125.40,
                'perimetre_mm' => 250.80,
                'image_coupe' => null,
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
                'section_largeur_mm' => 55.00,
                'section_hauteur_mm' => 35.00,
                'epaisseur_paroi_mm' => 1.80,
                'poids_lineaire_kg_m' => 2.650,
                'poids_lineaire_lbs_ft' => 1.781,
                'moment_inertie_cm4' => 108.20,
                'perimetre_mm' => 222.23,
                'image_coupe' => null,
                'est_disponible' => true,
            ],

            // ============================================================
            // JOINTS
            // ============================================================
            [
                'reference' => 'JOINT-001',
                'designation' => "Joint d'étanchéité EPDM 8mm",
                'slug' => 'joint-epdm-8mm',
                'type_composant_id' => 2, // Joint
                'gamme_id' => null,
                'matiere' => 'EPDM',
                'longueur_barre_mm' => null,
                'section_largeur_mm' => 8.00,
                'section_hauteur_mm' => 5.00,
                'epaisseur_paroi_mm' => null,
                'poids_lineaire_kg_m' => 0.120,
                'poids_lineaire_lbs_ft' => 0.081,
                'moment_inertie_cm4' => null,
                'perimetre_mm' => null,
                'image_coupe' => null,
                'est_disponible' => true,
            ],

            // ============================================================
            // QUINCAILLERIE
            // ============================================================
            [
                'reference' => 'QUIN-001',
                'designation' => 'Serrure multipoints 45mm',
                'slug' => 'serrure-multipoints-45mm',
                'type_composant_id' => 3, // Quincaillerie
                'gamme_id' => 1,
                'matiere' => 'Acier zingué',
                'longueur_barre_mm' => null,
                'section_largeur_mm' => null,
                'section_hauteur_mm' => null,
                'epaisseur_paroi_mm' => null,
                'poids_lineaire_kg_m' => null,
                'poids_lineaire_lbs_ft' => null,
                'moment_inertie_cm4' => null,
                'perimetre_mm' => null,
                'image_coupe' => null,
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
                'section_largeur_mm' => null,
                'section_hauteur_mm' => null,
                'epaisseur_paroi_mm' => null,
                'poids_lineaire_kg_m' => null,
                'poids_lineaire_lbs_ft' => null,
                'moment_inertie_cm4' => null,
                'perimetre_mm' => null,
                'image_coupe' => null,
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