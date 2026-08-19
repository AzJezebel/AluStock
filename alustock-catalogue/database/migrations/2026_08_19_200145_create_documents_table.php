<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('documents', function (Blueprint $table) {
            $table->id();
            $table->string('chemin_fichier', 255);
            $table->string('titre', 200);
            $table->text('description')->nullable();
            $table->enum('type_document', ['plan', 'fiche_technique', 'certificat', 'notice', 'autre']);
            $table->integer('taille_octets')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('documents');
    }
};