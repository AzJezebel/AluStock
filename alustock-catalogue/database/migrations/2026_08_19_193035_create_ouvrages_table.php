<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('ouvrages', function (Blueprint $table) {
            $table->id();
            $table->string('reference', 50)->unique();
            $table->string('nom', 200);
            $table->string('slug', 220)->unique();
            $table->foreignId('gamme_id')
                ->nullable()
                ->constrained('gammes')
                ->nullOnDelete();
            $table->foreignId('categorie_id')
                ->nullable()
                ->constrained('categories')
                ->nullOnDelete();
            $table->text('description_courte')->nullable();
            $table->longText('description_technique')->nullable();
            $table->integer('largeur_min_mm')->nullable();
            $table->integer('largeur_max_mm')->nullable();
            $table->integer('hauteur_min_mm')->nullable();
            $table->integer('hauteur_max_mm')->nullable();
            $table->string('performance_thermique', 50)->nullable();
            $table->string('performance_acoustique', 50)->nullable();
            $table->string('image_principale', 255)->nullable();
            $table->boolean('est_actif')->default(true);
            $table->timestamps();

            // Index composites pour optimiser les filtres
            $table->index(['gamme_id', 'est_actif']);
            $table->index(['categorie_id', 'est_actif']);
            $table->index('reference');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('ouvrages');
    }
};