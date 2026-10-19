<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Images du site (hero, « À propos ») : le lien emplacement → fichier est en base.
     * Les fichiers eux-mêmes restent sur le disque (config vitrine.media.disk).
     */
    public function up(): void
    {
        Schema::create('site_images', function (Blueprint $table) {
            $table->id();
            $table->string('slot', 50);            // hero | about
            $table->string('file_path');           // chemin sur le disque, ex. vitrine/hero/abc.jpg
            $table->string('alt')->default('');
            $table->unsignedInteger('order')->default(0);
            $table->timestamps();

            $table->index(['slot', 'order']);
        });

        $this->importLegacyJson();
    }

    public function down(): void
    {
        Schema::dropIfExists('site_images');
    }

    /** Reprend l'ancien fichier storage/app/vitrine-media.json s'il existe (une seule fois). */
    private function importLegacyJson(): void
    {
        $file = storage_path('app/vitrine-media.json');
        if (! is_file($file)) {
            return;
        }

        $data = json_decode((string) file_get_contents($file), true);
        if (is_array($data)) {
            $now = now();
            foreach ($data as $slot => $items) {
                foreach (array_values((array) $items) as $order => $item) {
                    if (empty($item['path'])) {
                        continue;
                    }
                    \DB::table('site_images')->insert([
                        'slot'       => $slot,
                        'file_path'  => $item['path'],
                        'alt'        => $item['alt'] ?? '',
                        'order'      => $order,
                        'created_at' => $now,
                        'updated_at' => $now,
                    ]);
                }
            }
        }

        rename($file, $file . '.migrated'); // l'ancien fichier n'est plus utilisé
    }
};