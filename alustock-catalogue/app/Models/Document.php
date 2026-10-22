<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphToMany;

class Document extends Model
{
    protected $table = 'documents';
    protected $fillable = [
        'chemin_fichier',
        'titre',
        'description',
        'type_document',
        'taille_octets',
    ];

    public function ouvrages(): MorphToMany
    {
        return $this->morphedByMany(Ouvrage::class, 'documentable', 'document_morph')
            ->withPivot('ordre')
            ->withTimestamps();
    }

    public function composants(): MorphToMany
    {
        return $this->morphedByMany(Composant::class, 'documentable', 'document_morph')
            ->withPivot('ordre')
            ->withTimestamps();
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