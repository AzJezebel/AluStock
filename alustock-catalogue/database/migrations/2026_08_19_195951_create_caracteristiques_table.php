<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('caracteristiques', function (Blueprint $table) {
            $table->id();
            $table->morphs('caracterisable');
            $table->string('cle', 100);
            $table->text('valeur');
            $table->string('unite', 20)->nullable();
            $table->integer('ordre_affichage')->default(0);
            $table->timestamps();

            $table->index(['caracterisable_id', 'cle']);
            $table->index('cle');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('caracteristiques');
    }
};