<?php
// app/Models/SiteImage.php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class SiteImage extends Model
{
    protected $fillable = ['slot', 'file_path', 'alt', 'order'];

    protected $casts = ['order' => 'integer'];

    public function scopeSlot($query, string $slot)
    {
        return $query->where('slot', $slot)->orderBy('order')->orderBy('id');
    }

    // Supprimer la ligne supprime aussi le fichier
    protected static function booted(): void
    {
        static::deleting(function (SiteImage $image) {
            Storage::disk(config('vitrine.media.disk', 'public'))->delete($image->file_path);
        });
    }
}