<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Document extends Model
{
    protected $fillable = [
        'chemin_fichier',
        'titre',
        'description',
        'type_document',
        'taille_octets',
    ];

    public function ouvrages(): BelongsToMany
    {
        return $this->belongsToMany(Ouvrage::class, 'document_association');
    }

    public function composants(): BelongsToMany
    {
        return $this->belongsToMany(Composant::class, 'document_association');
    }

    public function getTailleFormattedAttribute()
    {
        if (!$this->taille_octets) return 'N/A';
        
        $bytes = $this->taille_octets;
        $units = ['B', 'KB', 'MB', 'GB'];
        $i = 0;
        while ($bytes >= 1024 && $i < count($units) - 1) {
            $bytes /= 1024;
            $i++;
        }
        return round($bytes, 2) . ' ' . $units[$i];
    }
}