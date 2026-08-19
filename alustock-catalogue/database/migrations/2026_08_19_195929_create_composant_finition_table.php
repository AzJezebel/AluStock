<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('composant_finition', function (Blueprint $table) {
            $table->foreignId('composant_id')
                ->constrained('composants')
                ->cascadeOnDelete();
            $table->foreignId('finition_id')
                ->constrained('finitions')
                ->cascadeOnDelete();
            $table->boolean('est_par_defaut')->default(false);
            $table->timestamps();

            $table->primary(['composant_id', 'finition_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('composant_finition');
    }
};