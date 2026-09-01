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
        Schema::create('media', function (Blueprint $table) {
            $table->id();
            
            // Polymorphic relations
            $table->string('model_type');
            $table->unsignedBigInteger('model_id');
            $table->string('collection_name')->default('default');
            
            // File information
            $table->string('file_name');
            $table->string('file_path');
            $table->unsignedBigInteger('file_size')->nullable();
            $table->string('mime_type')->nullable();
            
            // Metadata
            $table->boolean('is_primary')->default(false);
            $table->integer('order')->default(0);
            $table->string('alt_text')->nullable();
            $table->string('title')->nullable();
            $table->text('description')->nullable();
            
            // Custom fields
            $table->json('custom_fields')->nullable();
            
            $table->timestamps();
            
            // Indexes
            $table->index(['model_type', 'model_id']);
            $table->index('collection_name');
            $table->index('is_primary');
            $table->index('order');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('media');
    }
};