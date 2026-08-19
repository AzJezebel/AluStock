<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('composants', function (Blueprint $table) {
            $table->id();
            $table->string('reference', 50)->unique();
            $table->string('designation', 200);
            $table->string('slug', 220)->unique();
            $table->foreignId('type_composant_id')
                ->nullable()
                ->constrained('types_composant')
                ->nullOnDelete();
            $table->foreignId('gamme_id')
                ->nullable()
                ->constrained('gammes')
                ->nullOnDelete();
            $table->string('matiere', 100)->nullable();
            $table->integer('longueur_barre_mm')->nullable();
            $table->decimal('poids_lineaire_kg_m', 10, 3)->nullable();
            $table->decimal('section_largeur_mm', 10, 2)->nullable();
            $table->decimal('section_hauteur_mm', 10, 2)->nullable();
            $table->decimal('epaisseur_paroi_mm', 10, 2)->nullable();
            $table->decimal('moment_inertie_x_cm4', 10, 2)->nullable();
            $table->decimal('moment_inertie_y_cm4', 10, 2)->nullable();
            $table->decimal('module_elasticite_x_cm3', 10, 2)->nullable();
            $table->decimal('module_elasticite_y_cm3', 10, 2)->nullable();
            $table->string('image_coupe', 255)->nullable();
            $table->boolean('est_disponible')->default(true);
            $table->timestamps();

            // Index composites pour optimiser les filtres
            $table->index(['type_composant_id', 'est_disponible']);
            $table->index(['gamme_id', 'est_disponible']);
            $table->index('reference');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('composants');
    }
};