# Catalogue

# Guide de démarrage — Sites profilés aluminium (stage dev web)

## 0. Contexte retenu

- **Stack** : Laravel (backend + rendu), libre côté front (Blade/Tailwind, éventuellement Livewire pour l'interactivité du catalogue).
- **Hébergement** : pas encore choisi → voir §6.
- **Organisation métier** : les pièces se rattachent à la fois à une **gamme de profilés** (ex : Gamme 45, Coulissant, Garde-corps) et à un **type d'ouvrage fini** (fenêtre, porte, véranda, verrière...).
- **Back-office** : oui, un mini CMS pour que l'entreprise gère elle-même le contenu (gammes, ouvrages, pièces, finitions, médias).

---

## 1. Cadrage (avant de coder quoi que ce soit)

Avant d'écrire une ligne de code, pose ces questions à ton maître de stage / à l'entreprise — elles conditionnent tout le reste :

- **Contenu existant** : ont-ils déjà un catalogue papier/PDF, un Excel de références, des fiches techniques fournisseur ? (ça évite de recréer les données à la main)
- **Volume réel** : combien de gammes ? combien de types d'ouvrages ? combien de pièces environ (10 ? 500 ? 5000) ? Ça influence fortement les besoins de recherche/filtre.
- **Visuels** : ont-ils des rendus 3D, photos de réalisations, plans techniques (DWG/PDF) exploitables pour le site "hero" ?
- **Utilisateurs du back-office** : combien de personnes, quel niveau technique (un mini CMS simple à prendre en main est préférable) ?
- **Durée du stage** : à caler avec le planning ci-dessous — si le stage est court (< 8 semaines), vise un scope réduit (1-2 gammes, 1-2 ouvrages en profondeur plutôt que tout le catalogue).
- **Identité visuelle** : charte graphique existante (logo, couleurs, typographies) ?
- **Objectif du site "hero"** : vitrine de réassurance commerciale (donner envie de contacter/demander un devis) — donc pas de logique métier, juste du beau contenu + un CTA contact.

---

## 2. Architecture des deux sites

Deux options possibles :

### Option A — Un seul projet Laravel, deux groupes de routes (recommandé pour un stage)
Une seule application Laravel, une seule base de données :
- `routes/web.php` → groupe `vitrine` (site hero, contenu léger, quasi statique + éventuellement quelques "réalisations types" tirées de la BDD catalogue)
- groupe `catalogue` → catalogue technique complet + back-office (ex: via **Filament**, qui génère un CMS admin très rapidement au-dessus de tes modèles Eloquent)

**Avantages** : un seul déploiement, une seule BDD, pas de duplication de données, gain de temps énorme sur la durée d'un stage.

### Option B — Deux projets Laravel séparés
Vitrine et catalogue = deux apps distinctes, éventuellement connectées via une API interne exposée par le catalogue.

**Avantages** : séparation nette des responsabilités, déploiements indépendants.
**Inconvénients** : plus de configuration, plus de temps perdu en boilerplate — à éviter sauf si le stage est long (> 3 mois) ou si c'est une contrainte imposée.

→ **Recommandation** : pars sur l'Option A, tu pourras toujours scinder plus tard si besoin.

---

## 3. Stack technique détaillée

| Brique | Choix suggéré | Pourquoi |
|---|---|---|
| Backend | Laravel 11+ | déjà choisi |
| Base de données | MySQL ou PostgreSQL | MySQL = plus simple à trouver en hébergement mutualisé FR |
| Front vitrine (hero) | Blade + Tailwind CSS | rapide, pas besoin de SPA pour un site de contenu |
| Front catalogue | Blade + Tailwind, + **Livewire** pour les filtres/recherche dynamiques | évite de faire une API séparée + un front JS lourd |
| Back-office | **Filament** (package Laravel) | admin CRUD généré automatiquement à partir des modèles Eloquent, gain de temps considérable pour un stage |
| Médias | `spatie/laravel-medialibrary` (optionnel) | gestion propre des images/fichiers liés aux modèles |
| Recherche | Filtres SQL simples au début, Laravel Scout + Meilisearch si le volume de pièces est important | inutile si < quelques centaines de pièces |

---

## 4. Planning type (à adapter à la durée réelle du stage)

1. **Semaine 1** — Cadrage, recueil des contenus existants, définition du périmètre exact (§1), choix définitif d'hébergement.
2. **Semaine 2** — Conception : MCD/MLD (voir fichier dédié), arborescence des deux sites, wireframes basse fidélité (Figma ou papier).
3. **Semaine 3** — Setup Laravel, migrations + seeders à partir du MLD, installation Filament, premiers modèles Eloquent + relations.
4. **Semaine 4-5** — Développement du back-office (CRUD gammes/ouvrages/pièces/finitions/médias), puis du catalogue public (liste, fiche modèle, fiche pièce, filtres).
5. **Semaine 6** — Développement du site vitrine (hero), intégration des rendus/photos, formulaire de contact/devis.
6. **Semaine 7** — Remplissage du contenu réel avec l'entreprise, tests, responsive, corrections.
7. **Semaine 8** — Déploiement, SEO de base (meta, sitemap, images optimisées), recette finale, documentation de passation.

---

## 5. Arborescence de pages (première version)

**Site hero (vitrine)**
- Accueil (rendus phares, accroche, CTA contact)
- Nos réalisations / Ce qui est faisable (par usage : fenêtres, vérandas, garde-corps...)
- À propos de l'entreprise
- Contact / Demande de devis

**Site catalogue technique**
- Accueil catalogue (accès par gamme / par type d'ouvrage)
- Liste des gammes → fiche gamme (modèles associés)
- Liste des types d'ouvrages → fiche type (modèles associés)
- Fiche modèle (liste des pièces qui le composent, quantités, images, docs techniques)
- Fiche pièce (référence, dimensions, poids, matière, finitions disponibles, fiche PDF téléchargeable)
- Recherche / filtres (par gamme, type de pièce, finition)
- **Back-office** (`/admin` via Filament) : gestion gammes, types d'ouvrages, modèles, pièces, finitions, médias, documents

---

## 6. Hébergement (pistes puisque pas encore choisi)

- **Mutualisé compatible Laravel** (o2switch, PlanetHoster) : simple, pas cher, suffisant pour un site vitrine + catalogue à trafic modéré. Bon compromis pour un projet de stage à faible budget.
- **VPS + Laravel Forge/Ploi** (Hetzner, OVH) : plus de contrôle, un peu plus technique à mettre en place, mais propre si l'entreprise veut garder la main après le stage.
- **PaaS** (Railway, Render) : pratique pour une démo/staging rapide pendant le développement, moins courant en prod pour ce type de site vitrine.

→ Pour un stage, commence en local (Laravel Sail/Herd) + un environnement de démo sur un PaaS gratuit/pas cher, et choisis l'hébergement définitif avec l'entreprise vers la moitié du stage (une fois le besoin de perf/budget plus clair).

---

## 7. Fichiers livrés séparément

- `mld-catalogue.mermaid` → schéma entité-association (MLD) du catalogue, prêt à visualiser
- `cas-utilisation.mermaid` → diagramme des cas d'utilisation (acteurs Visiteur / Administrateur)
- Dictionnaire de données MCD/MLD détaillé ci-dessous

---

## 8. MCD — Modèle Conceptuel de Données (texte, notation Merise)

**Entités principales**

- `GAMME` (nom, slug, description, image de couverture)
- `TYPE_OUVRAGE` (nom, slug, description) — ex : fenêtre, porte, véranda, verrière
- `MODELE` — une déclinaison concrète d'un ouvrage dans une gamme (ex : "Fenêtre coulissante 2 vantaux — Gamme 45")
- `TYPE_PIECE` (nom, slug) — ex : profilé, joint, quincaillerie, accessoire
- `PIECE` (référence, désignation, description, longueur de barre, poids au mètre linéaire, matière)
- `CARACTERISTIQUE` (clé, valeur, unité) 
- `FINITION` (nom, code RAL, type : laquage/anodisation)
- `MEDIA` (fichier image/rendu, légende) — rattachable à une gamme, un ouvrage, un modèle ou une pièce
- `DOCUMENT` (fichier PDF/plan, titre) — rattachable à un modèle ou une pièce
- `DEMANDE_CONTACT` (nom, email, téléphone, message) — formulaire du site vitrine
- `UTILISATEUR` comptes du back-office uniquement

**Relations et cardinalités**

- `GAMME (1,n) —— compose —— (1,1) MODELE` : une gamme regroupe plusieurs modèles ; un modèle appartient à une seule gamme.
- `TYPE_OUVRAGE (1,n) —— décline —— (1,1) MODELE` : un type d'ouvrage regroupe plusieurs modèles ; un modèle appartient à un seul type.
- `MODELE (1,n) —— COMPOSER_DE —— (1,n) PIECE` (association porteuse d'attributs : `quantité`, `unité`, `ordre`) : un modèle est composé de plusieurs pièces, une pièce peut entrer dans plusieurs modèles.
- `TYPE_PIECE (1,n) —— catégorise —— (1,1) PIECE` : une pièce a exactement un type ; un type regroupe plusieurs pièces.
- `PIECE (0,n) —— disponible_en —— (0,n) FINITION` : une pièce peut exister en plusieurs finitions, une finition s'applique à plusieurs pièces.
- `GAMME (0,1) —— rattache —— (0,n) PIECE` : une pièce peut être spécifique à une gamme (optionnel), une gamme rassemble plusieurs pièces.
- `MODELE (0,n) —— concerne —— (0,1) DEMANDE_CONTACT` : une demande de contact peut porter sur un modèle précis (optionnel).

- Remarque importante — specs techniques variables selon la catégorie : une vis n'a pas les mêmes caractéristiques qu'un profilé (diamètre/pas de vis vs longueur de barre/section). Plutôt que de multiplier des colonnes vides selon la catégorie, on ajoute une entité CARACTERISTIQUE en clé-valeur (pattern EAV léger) rattachée au COMPOSANT. Ça permet d'avoir des tableaux de specs dynamiques comme sur McMaster, sans figer un schéma rigide. Le socle commun (référence, désignation, poids, matière) reste en colonnes fixes sur PIECE ; tout ce qui est spécifique à une catégorie va dans CARACTERISTIQUE.

---

## 9. MLD — Modèle Logique de Données (tables relationnelles)

```
gammes
  id (PK), nom, slug, description, image_couverture, ordre, timestamps

types_ouvrages
  id (PK), nom, slug, description, image_couverture, ordre, timestamps

modeles
  id (PK), nom, slug, description, image_couverture,
  gamme_id (FK -> gammes.id),
  type_ouvrage_id (FK -> types_ouvrages.id),
  timestamps

types_pieces
  id (PK), nom, slug, timestamps

pieces
  id (PK), reference, designation, description,
  type_piece_id (FK -> types_pieces.id),
  gamme_id (FK -> gammes.id, nullable),
  longueur_barre_mm, poids_ml_kg, matiere,
  timestamps

finitions
  id (PK), nom, code_ral, type, timestamps

piece_finition   -- table pivot
  piece_id (FK -> pieces.id), finition_id (FK -> finitions.id)

modele_piece     -- table pivot avec attributs
  id (PK), modele_id (FK -> modeles.id), piece_id (FK -> pieces.id),
  quantite, unite, position_ordre, note

medias
  id (PK), mediable_id, mediable_type (polymorphique Laravel),
  chemin, type (photo/rendu_3d/plan), legende, ordre, timestamps

documents
  id (PK), documentable_id, documentable_type (polymorphique Laravel),
  titre, fichier, type (pdf/dwg/notice), timestamps

```

Les tables `medias` et `documents` utilisent le pattern **polymorphique** de Laravel (`morphTo`/`morphMany`) : une seule table peut être rattachée à plusieurs types d'entités (gamme, modèle ou pièce) sans dupliquer la structure.

---

## 10. Prochaines étapes concrètes

1. Valide le périmètre exact avec l'entreprise (§1).
2. Ajuste le MCD/MLD si leur organisation réelle diffère (ex : s'il existe des sous-gammes, des variantes de couleur par défaut, etc.).
3. Crée le projet Laravel, installe Filament, transforme le MLD en migrations.
4. Construis d'abord le back-office (Filament te donne un CRUD quasi gratuit), puis le catalogue public, puis le site vitrine.


============================================================================================================================================================================================================================================================


1	  Créer les migrations à partir du script SQL	        ☐
2	  Créer les modèles Eloquent avec relations	          ☐
3	  Implémenter les Seeders (gammes, types, finitions)	☐
4	  Créer les contrôleurs CRUD pour le backoffice	      ☐
5	  Implémenter la recherche de pièces (scope + index)	☐
6	  Implémenter les filtres (gamme, type, finition)	    ☐
7	  Créer les vues du catalogue (listing + fiche)	      ☐
8	  Ajouter l'upload de médias et documents	            ☐
9	  Implémenter le système EAV pour caractéristiques	  ☐
10  Tester les performances (requêtes N+1)	            ☐

Jour 22-23 : Tests & Responsive
Vérifier sur mobile/tablet/desktop

Tester les performances Lighthouse

Ajouter meta tags OG pour partage

Jour 24-25 : Documentation & Livraison
README avec installation

Commentaires dans le code

Vidéo de démonstration (optionnel)



Tous les specs ds CARACTERISTIQUE ou garder approcher hybride (need catalogue)

Aspect	                          Colonnes fixes	                                  EAV
Performance des filtres	     ⭐⭐⭐⭐⭐ (index direct)	                ⭐⭐ (jointure + condition sur clé/valeur)
Lisibilité du code	         $piece->poids_lineaire_kg_m	                $piece->caracteristiques()->where('cle','poids')->first()->valeur
Validation	                 Native (type DECIMAL, INT)                   À gérer manuellement
Évolution	                   Ajout d'une colonne = migration	            Ajout d'une ligne = simple seed
Intégrité des données	       Garantie par le SGBD	                        À garantir par le code