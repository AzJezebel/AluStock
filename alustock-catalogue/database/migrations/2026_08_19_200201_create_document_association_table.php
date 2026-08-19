<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('document_association', function (Blueprint $table) {
            $table->foreignId('document_id')
                ->constrained('documents')
                ->cascadeOnDelete();
            $table->foreignId('ouvrage_id')
                ->nullable()
                ->constrained('ouvrages')
                ->cascadeOnDelete();
            $table->foreignId('composant_id')
                ->nullable()
                ->constrained('composants')
                ->cascadeOnDelete();
            $table->timestamps();

            $table->index(['ouvrage_id', 'composant_id']);
            $table->index(['document_id', 'ouvrage_id']);
            $table->index(['document_id', 'composant_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('document_association');
    }
};