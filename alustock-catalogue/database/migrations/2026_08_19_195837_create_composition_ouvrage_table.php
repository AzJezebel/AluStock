<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('composition_ouvrage', function (Blueprint $table) {
            $table->foreignId('ouvrage_id')
                ->constrained('ouvrages')
                ->cascadeOnDelete();
            $table->foreignId('composant_id')
                ->constrained('composants')
                ->cascadeOnDelete();
            $table->decimal('quantite', 10, 2)->default(1);
            $table->string('unite', 20)->default('u');
            $table->integer('ordre')->default(0);
            $table->integer('longueur_coupe_mm')->nullable();
            $table->text('commentaire')->nullable();
            $table->timestamps();

            $table->primary(['ouvrage_id', 'composant_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('composition_ouvrage');
    }
};