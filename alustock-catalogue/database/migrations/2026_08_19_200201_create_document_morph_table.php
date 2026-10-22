<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Liaison polymorphe document <-> ouvrage / composant (même structure que media_morph).
     * Une ligne = un document lié à UN objet : plus de clés étrangères nullables.
     * documentable_type contient l'alias du morphMap ('ouvrage', 'composant').
     */
    public function up(): void
    {
        Schema::create('document_morph', function (Blueprint $table) {
            $table->foreignId('document_id')
                ->constrained('documents')
                ->cascadeOnDelete();

            $table->morphs('documentable'); // documentable_id + documentable_type

            $table->integer('ordre')->default(0);

            $table->timestamps();

            $table->primary(['document_id', 'documentable_id', 'documentable_type']);
            // morphs() crée déjà l'index (documentable_type, documentable_id)
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('document_morph');
    }
};
