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
            $table->string('chemin_fichier', 255);
            $table->string('titre', 200)->nullable();
            $table->text('description')->nullable();
            $table->enum('type_media', ['image', 'rendu_3d', 'plan', 'video']);
            $table->boolean('est_principal')->default(false);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('medias');
    }
};