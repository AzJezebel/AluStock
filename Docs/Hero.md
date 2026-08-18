□ Site responsive (mobile-first)
□ Hero avec vidéo/image + 3D
□ Grille de réalisations avec filtres
□ Page détail avec galerie
□ Page "Nos savoir-faire" placeholder
□ Animations au scroll
□ Modèles 3D interactifs
□ Optimisé (Lighthouse > 90)
□ Déployé en ligne
□ Code documenté

Background vidéo ou image plein écran

Titre principal + sous-titre

CTA "Découvrir nos réalisations" (scroll smooth)

Overlay avec dégradé

Jour 22-23 : Tests & Responsive
Vérifier sur mobile/tablet/desktop

Tester les performances Lighthouse

Ajouter meta tags OG pour partage

Jour 24-25 : Documentation & Livraison
README avec installation

Commentaires dans le code

Vidéo de démonstration (optionnel)



# Structure de dossiers

app/
├── Http/
│   └── Controllers/
│       ├── PageController.php      # Pages statiques
│       └── RealisationController.php # Affichage réalisations
├── Models/
│   └── Realisation.php
database/
├── migrations/
│   └── create_realisations_table.php
├── seeders/
│   └── RealisationSeeder.php       # Données de démo
resources/
├── views/
│   ├── layouts/
│   │   └── app.blade.php
│   ├── pages/
│   │   ├── home.blade.php
│   │   ├── savoir-faire.blade.php
│   │   └── realisation-detail.blade.php
│   └── components/
│       ├── hero.blade.php
│       └── realisation-card.blade.php
public/
├── assets/                          # Images, vidéos, modèles 3D
│   ├── images/
│   ├── videos/
│   └── models/


// routes/web.php
Route::get('/', [PageController::class, 'home'])->name('home');
Route::get('/savoir-faire', [PageController::class, 'savoirFaire'])->name('savoir-faire');
Route::get('/realisations/{slug}', [RealisationController::class, 'show'])->name('realisation.show');

// App\Http\Controllers\PageController.php
public function home()
{
    $realisations = Realisation::orderBy('order')->get();
    $featured = Realisation::where('is_featured', true)->first();
    return view('pages.home', compact('realisations', 'featured'));
}