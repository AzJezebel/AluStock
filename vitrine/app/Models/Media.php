<?php
// app/Models/Media.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Media extends Model
{
    use HasFactory;

    protected $fillable = [
        'model_type',
        'model_id',
        'file_name',
        'file_path',
        'file_size',
        'mime_type',
        'order',
        'is_primary',
        'alt_text',
        'title',
        'description',
        'custom_fields',
    ];

    protected $casts = [
        'is_primary'    => 'boolean',
        'file_size'     => 'integer',
        'order'         => 'integer',
        'custom_fields' => 'array',
    ];

    /** Disque où sont rangés les fichiers (config/vitrine.php → media.disk). */
    public static function disk(): string
    {
        return config('vitrine.media.disk', 'public');
    }

    public function model()
    {
        return $this->morphTo();
    }

    public function getUrlAttribute()
    {
        return asset('storage/' . $this->file_path);
    }

    public function getSizeAttribute()
    {
        $size = $this->file_size ?? 0;
        $units = ['B', 'KB', 'MB', 'GB'];
        $i = 0;
        while ($size >= 1024 && $i < count($units) - 1) {
            $size /= 1024;
            $i++;
        }
        return round($size, 2) . ' ' . $units[$i];
    }

    /** Image de couverture (une seule par modèle). */
    public function scopePrimary($query)
    {
        return $query->where('is_primary', true);
    }

    /** Images secondaires (galerie). */
    public function scopeGallery($query)
    {
        return $query->where('is_primary', false);
    }

    public function scopeOrdered($query)
    {
        return $query->orderBy('order', 'asc');
    }

    // Supprimer la ligne supprime aussi le fichier : plus de code de nettoyage dans les contrôleurs
    protected static function booted(): void
    {
        static::deleting(function (Media $media) {
            Storage::disk(static::disk())->delete($media->file_path);
        });
    }
}