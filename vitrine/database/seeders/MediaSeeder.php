<?php
// database/seeders/MediaSeeder.php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Media;
use App\Models\Ouvrage;

class MediaSeeder extends Seeder
{
    public function run()
    {
        // Ajouter des médias pour les ouvrages existants
        $ouvrages = Ouvrage::all();
        
        foreach ($ouvrages as $index => $ouvrage) {
            Media::create([
                'model_type' => Ouvrage::class,
                'model_id' => $ouvrage->id,
                'collection_name' => 'main',
                'file_name' => 'ouvrage-' . ($index + 1) . '.jpg',
                'file_path' => 'ouvrages/main/' . $ouvrage->id . '/image.jpg',
                'file_size' => 1024 * 100,
                'mime_type' => 'image/jpeg',
                'is_primary' => true,
                'order' => 0,
                'alt_text' => $ouvrage->titre,
            ]);
        }
    }
}