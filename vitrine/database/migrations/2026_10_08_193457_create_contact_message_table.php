<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Trace de chaque message envoyé via le formulaire de contact.
     * Le message est enregistré AVANT l'envoi du courriel : même si le SMTP
     * tombe, rien n'est perdu et `statut` / `erreur` disent ce qui s'est passé.
     */
    public function up(): void
    {
        Schema::create('contact_messages', function (Blueprint $table) {
            $table->id();
            $table->string('nom', 120);
            $table->string('email', 190)->index();
            $table->string('entreprise', 150)->nullable();
            $table->string('sujet', 190);
            $table->text('message');

            // Traçabilité
            $table->ipAddress('ip')->nullable();
            $table->string('user_agent', 255)->nullable();

            // Suivi d'envoi : en_attente | envoye | echec
            $table->string('statut', 20)->default('en_attente')->index();
            $table->text('erreur')->nullable();
            $table->timestamp('envoye_le')->nullable();

            // Suivi interne (optionnel, pour une future page admin)
            $table->timestamp('lu_le')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('contact_messages');
    }
};