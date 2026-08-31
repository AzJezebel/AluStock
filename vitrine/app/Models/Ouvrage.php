<?php
// app/Models/Ouvrage.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ouvrage extends Model
{
    use HasFactory;

    protected $fillable = [
        'titre',
        'description',
        'reference',
        'categorie_id',
        'gamme_id',
        'is_active',
        'image',
        'date_realisation'
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'date_realisation' => 'date'
    ];

    public function categorie()
    {
        return $this->belongsTo(Categorie::class);
    }

    public function gamme()
    {
        return $this->belongsTo(Gamme::class);
    }

    public function scopeActif($query)
    {
        return $query->where('is_active', true);
    }
}