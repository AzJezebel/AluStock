<?php
// database/migrations/2024_01_01_000003_create_ouvrages_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('ouvrages', function (Blueprint $table) {
            $table->id();
            $table->string('titre');
            $table->string('slug')->unique();
            $table->text('description')->nullable();
            $table->string('reference')->unique();
            $table->foreignId('categorie_id')->nullable()->constrained()->onDelete('set null');
            $table->foreignId('gamme_id')->nullable()->constrained()->onDelete('set null');
            $table->string('image')->nullable();
            $table->text('images')->nullable(); // Pour stocker plusieurs images en JSON
            $table->date('date_realisation')->nullable();
            $table->string('client')->nullable();
            $table->string('localisation')->nullable();
            $table->text('specifications')->nullable();
            $table->boolean('is_active')->default(true);
            $table->boolean('is_featured')->default(false);
            $table->integer('views')->default(0);
            $table->timestamps();
            
            // Index pour les recherches
            $table->index(['categorie_id', 'gamme_id']);
            $table->index('is_active');
            $table->index('date_realisation');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ouvrages');
    }
};