<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('medias', function (Blueprint $table) {
            $table->id();
            
            // Fichiers
            $table->string('chemin_fichier', 255);
            $table->string('thumbnail', 255)->nullable(); // Miniature 300x300
            
            // Métadonnées
            $table->string('titre', 200)->nullable();
            $table->string('alt_text', 200)->nullable(); // SEO / accessibilité
            $table->text('description')->nullable();
            
            // Type
            $table->enum('type_media', ['schema', 'photo', 'rendu_3d'])->default('schema');
            
            // Métadonnées techniques
            $table->integer('taille_octets')->nullable();
            $table->string('mime_type', 100)->nullable();
            $table->integer('largeur_px')->nullable();
            $table->integer('hauteur_px')->nullable();
            
            // État
            $table->boolean('est_principal')->default(false);
            
            $table->timestamps();
            $table->softDeletes(); // Historique des versions (soft delete)
            
            // Index
            $table->index('type_media');
            $table->index('est_principal');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('medias');
    }
};