<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Médias rattachés à n'importe quel modèle (relation polymorphe).
     * Rôle d'une image : is_primary = true → couverture (une seule par modèle),
     * false → galerie. Pas de colonne « collection ».
     */
    public function up(): void
    {
        Schema::create('media', function (Blueprint $table) {
            $table->id();

            // Relation polymorphe
            $table->string('model_type');
            $table->unsignedBigInteger('model_id');

            // Fichier
            $table->string('file_name');
            $table->string('file_path');
            $table->unsignedBigInteger('file_size')->nullable();
            $table->string('mime_type')->nullable();

            // Métadonnées
            $table->boolean('is_primary')->default(false);
            $table->integer('order')->default(0);
            $table->string('alt_text')->nullable();
            $table->string('title')->nullable();
            $table->text('description')->nullable();
            $table->json('custom_fields')->nullable();

            $table->timestamps();

            // « tous les médias d'un modèle » et « sa couverture » passent par le même index
            $table->index(['model_type', 'model_id', 'is_primary']);
            $table->index('order');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('media');
    }
};