<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('categories', function (Blueprint $table) {
            $table->id();
            $table->string('nom', 100)->unique();
            $table->string('slug', 120)->unique();
            $table->text('description')->nullable();
            $table->string('image')->nullable(); // chemin relatif sur le disque public
            $table->unsignedSmallInteger('ordre')->default(0); // ordre d'affichage (le plus petit d'abord)
            $table->timestamps();

            $table->index('ordre');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('categories');
    }
};