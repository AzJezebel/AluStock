<?php
// database/migrations/2026_08_31_193644_create_ouvrages_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('ouvrages', function (Blueprint $table) {
            $table->id();
            $table->string('titre');
            $table->string('slug')->unique();
            $table->string('reference')->unique();
            $table->text('description')->nullable();
            $table->text('specifications')->nullable();
            $table->foreignId('categorie_id')->nullable()->constrained('categories')->nullOnDelete();
            $table->date('date_realisation')->nullable();
            $table->string('client')->nullable();
            $table->string('localisation')->nullable();
            $table->boolean('is_active')->default(true);
            $table->boolean('is_featured')->default(false);
            $table->unsignedInteger('views')->default(0);
            $table->timestamps();

            $table->index('is_active');
            $table->index('is_featured');
            $table->index('date_realisation');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('ouvrages');
    }
};