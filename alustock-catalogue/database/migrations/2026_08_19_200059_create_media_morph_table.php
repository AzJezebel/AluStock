<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
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
    }

    public function down(): void
    {
        Schema::dropIfExists('media_morph');
    }
};