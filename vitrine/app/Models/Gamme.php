<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Gamme extends Model
{
    use HasFactory;

    protected $fillable = [
        'nom',
        'slug',
        'description',
        'icone',
        'couleur',
        'is_active',
        'ordre'
    ];

    protected $casts = [
        'is_active' => 'boolean'
    ];

    public function ouvrages()
    {
        return $this->hasMany(Ouvrage::class);
    }
}