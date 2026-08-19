# Catalogue

#	Nom du fichier	                                                  Table créée                       	Dépend de
01	create_gammes_table.php	                                    gammes	                                        -
02	create_categories_table.php	                                categories	                                    -
03	create_types_composant_table.php	                        types_composant                     	        -
04	create_ouvrages_table.php	                                ouvrages	                        gammes, categories
05	create_composants_table.php	                                composants	                        types_composant, gammes
06	create_composition_ouvrage_table.php	                    composition_ouvrage	                ouvrages, composants
07	create_finitions_table.php	                                finitions	                                    -
08	create_composant_finition_table.php	                        composant_finition	                composants, finitions
09	create_caracteristiques_table.php	                        caracteristiques	                composants, ouvrages (polymorphique)
10	create_medias_table.php	                                    medias	                                        -
11	create_media_morph_table.php	                            media_morph                         medias
12	create_documents_table.php	                                documents	                                    -
13	create_document_association_table.php	                    document_association                documents, ouvrages, composants



<?php

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
        // ============================================================
        // 1. TABLES PRINCIPALES
        // ============================================================

        // 1.1 Gammes
        Schema::create('gammes', function (Blueprint $table) {
            $table->id();
            $table->string('nom', 100)->unique();
            $table->string('slug', 120)->unique();
            $table->text('description')->nullable();
            $table->string('image_cover', 255)->nullable();
            $table->integer('ordre_affichage')->default(0);
            $table->timestamps();
        });

        // 1.2 Catégories
        Schema::create('categories', function (Blueprint $table) {
            $table->id();
            $table->string('nom', 100)->unique();
            $table->string('slug', 120)->unique();
            $table->text('description')->nullable();
            $table->string('icone', 50)->nullable();
            $table->timestamps();
        });

        // 1.3 Types de composant
        Schema::create('types_composant', function (Blueprint $table) {
            $table->id();
            $table->string('nom', 100)->unique();
            $table->string('slug', 120)->unique();
            $table->text('description')->nullable();
            $table->timestamps();
        });

        // 1.4 Ouvrages
        Schema::create('ouvrages', function (Blueprint $table) {
            $table->id();
            $table->string('reference', 50)->unique();
            $table->string('nom', 200);
            $table->string('slug', 220)->unique();
            $table->foreignId('gamme_id')
                ->nullable()
                ->constrained('gammes')
                ->nullOnDelete();
            $table->foreignId('categorie_id')
                ->nullable()
                ->constrained('categories')
                ->nullOnDelete();
            $table->text('description_courte')->nullable();
            $table->longText('description_technique')->nullable();
            $table->integer('largeur_min_mm')->nullable();
            $table->integer('largeur_max_mm')->nullable();
            $table->integer('hauteur_min_mm')->nullable();
            $table->integer('hauteur_max_mm')->nullable();
            $table->string('performance_thermique', 50)->nullable();
            $table->string('performance_acoustique', 50)->nullable();
            $table->string('image_principale', 255)->nullable();
            $table->boolean('est_actif')->default(true);
            $table->timestamps();

            // Index composites pour optimiser les filtres
            $table->index(['gamme_id', 'est_actif']);
            $table->index(['categorie_id', 'est_actif']);
            $table->index('reference');
        });

        // 1.5 Composants
        Schema::create('composants', function (Blueprint $table) {
            $table->id();
            $table->string('reference', 50)->unique();
            $table->string('designation', 200);
            $table->string('slug', 220)->unique();
            $table->foreignId('type_composant_id')
                ->nullable()
                ->constrained('types_composant')
                ->nullOnDelete();
            $table->foreignId('gamme_id')
                ->nullable()
                ->constrained('gammes')
                ->nullOnDelete();
            $table->string('matiere', 100)->nullable();
            $table->integer('longueur_barre_mm')->nullable();
            $table->decimal('poids_lineaire_kg_m', 10, 3)->nullable();
            $table->decimal('section_largeur_mm', 10, 2)->nullable();
            $table->decimal('section_hauteur_mm', 10, 2)->nullable();
            $table->decimal('epaisseur_paroi_mm', 10, 2)->nullable();
            $table->decimal('moment_inertie_x_cm4', 10, 2)->nullable();
            $table->decimal('moment_inertie_y_cm4', 10, 2)->nullable();
            $table->decimal('module_elasticite_x_cm3', 10, 2)->nullable();
            $table->decimal('module_elasticite_y_cm3', 10, 2)->nullable();
            $table->string('image_coupe', 255)->nullable();
            $table->boolean('est_disponible')->default(true);
            $table->timestamps();

            // Index composites pour optimiser les filtres
            $table->index(['type_composant_id', 'est_disponible']);
            $table->index(['gamme_id', 'est_disponible']);
            $table->index('reference');
        });

        // ============================================================
        // 2. TABLES DE LIAISON
        // ============================================================

        // 2.1 Composition ouvrage (Ouvrage ↔ Composant)
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

        // 2.2 Finitions
        Schema::create('finitions', function (Blueprint $table) {
            $table->id();
            $table->string('nom', 100)->unique();
            $table->string('slug', 120)->unique();
            $table->string('code_ral', 10)->nullable();
            $table->enum('type_finition', ['laquage', 'anodisation', 'brut', 'poudre']);
            $table->text('description')->nullable();
            $table->timestamps();
        });

        // 2.3 Composant finition (Composant ↔ Finition)
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

        // ============================================================
        // 3. CARACTÉRISTIQUES TECHNIQUES (EAV)
        // ============================================================

        // 3.1 Caractéristiques pour les composants ET les ouvrages
        Schema::create('caracteristiques', function (Blueprint $table) {
            $table->id();
            $table->morphs('caracterisable'); // composant_id ou ouvrage_id + type
            $table->string('cle', 100);
            $table->text('valeur');
            $table->string('unite', 20)->nullable();
            $table->integer('ordre_affichage')->default(0);
            $table->timestamps();

            $table->index(['caracterisable_type', 'caracterisable_id']);
            $table->index(['caracterisable_id', 'cle']);
            $table->index('cle');
        });

        // ============================================================
        // 4. MÉDIAS ET DOCUMENTS
        // ============================================================

        // 4.1 Médias
        Schema::create('medias', function (Blueprint $table) {
            $table->id();
            $table->string('chemin_fichier', 255);
            $table->string('titre', 200)->nullable();
            $table->text('description')->nullable();
            $table->enum('type_media', ['image', 'rendu_3d', 'plan', 'video']);
            $table->boolean('est_principal')->default(false);
            $table->timestamps();
        });

        // 4.2 Media morph (polymorphique)
        Schema::create('media_morph', function (Blueprint $table) {
            $table->foreignId('media_id')
                ->constrained('medias')
                ->cascadeOnDelete();
            $table->morphs('mediable');
            $table->integer('ordre')->default(0);
            $table->timestamps();

            $table->primary(['media_id', 'mediable_id', 'mediable_type']);
            $table->index(['mediable_id', 'mediable_type']);
        });

        // 4.3 Documents
        Schema::create('documents', function (Blueprint $table) {
            $table->id();
            $table->string('chemin_fichier', 255);
            $table->string('titre', 200);
            $table->text('description')->nullable();
            $table->enum('type_document', ['plan', 'fiche_technique', 'certificat', 'notice', 'autre']);
            $table->integer('taille_octets')->nullable();
            $table->timestamps();
        });

        // 4.4 Document association
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

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // L'ordre est important : on supprime d'abord les tables enfants
        Schema::dropIfExists('document_association');
        Schema::dropIfExists('documents');
        Schema::dropIfExists('media_morph');
        Schema::dropIfExists('medias');
        Schema::dropIfExists('caracteristiques');
        Schema::dropIfExists('composant_finition');
        Schema::dropIfExists('finitions');
        Schema::dropIfExists('composition_ouvrage');
        Schema::dropIfExists('composants');
        Schema::dropIfExists('ouvrages');
        Schema::dropIfExists('types_composant');
        Schema::dropIfExists('categories');
        Schema::dropIfExists('gammes');
    }
};

