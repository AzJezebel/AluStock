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

[REALISATION]
- id (PK)
- title
- slug (UK)
- description
- category
- images (multivalué)
- videos (multivalué)
- model_3d_url
- is_featured
- order
- created_at
- updated_at

REALISATION (id, title, slug, description, category, images, videos, model_3d_url, is_featured, order, created_at, updated_at)

Acteur: Visiteur

1. Consulter l'accueil
   - Voir la Hero avec vidéo/3D
   - Voir les réalisations en grille
   - Filtrer par catégorie (JS)

2. Consulter une réalisation
   - Voir les images/vidéos
   - Visualiser le modèle 3D interactif

3. Consulter "Nos savoir-faire"
   - Voir les compétences de l'entreprise

4. Navigation
   - Menu responsive
   - Scroll fluide


   /* Palette Aluminium */
--primary: #2C3E50;      /* Gris anthracite */
--secondary: #5D6D7E;     /* Gris aluminium */
--accent: #E74C3C;        /* Rouge pour les CTA */
--light: #ECF0F1;         /* Fond clair */
--dark: #1A252F;          /* Fond sombre pour Hero */

/* Typographie */
--font-heading: 'Playfair Display', serif;
--font-body: 'Inter', sans-serif;