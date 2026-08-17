// Modèle Gamme
public function modeles() {
    return $this->hasMany(Modele::class);
}

// Modèle Modele
public function gamme() {
    return $this->belongsTo(Gamme::class);
}
public function typeOuvrage() {
    return $this->belongsTo(TypeOuvrage::class);
}
public function pieces() {
    return $this->belongsToMany(Piece::class, 'composition_modele')
                ->withPivot('quantite', 'unite', 'ordre', 'longueur_coupe_mm', 'commentaire')
                ->orderBy('pivot_ordre');
}

// Modèle Piece
public function typePiece() {
    return $this->belongsTo(TypePiece::class);
}
public function gamme() {
    return $this->belongsTo(Gamme::class);
}
public function modeles() {
    return $this->belongsToMany(Modele::class, 'composition_modele')
                ->withPivot('quantite', 'unite', 'ordre', 'longueur_coupe_mm', 'commentaire');
}
public function finitions() {
    return $this->belongsToMany(Finition::class, 'piece_finition')
                ->withPivot('est_par_defaut');
}
public function caracteristiques() {
    return $this->hasMany(Caracteristique::class)->orderBy('ordre_affichage');
}
public function medias() {
    return $this->morphToMany(Media::class, 'mediable', 'media_morph');
}
public function documents() {
    return $this->belongsToMany(Document::class, 'document_association');
}