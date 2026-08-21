# Catalogue

┌─────────────────────────────────────────────────────────────────────────────────────┐
│                          MODÈLE LOGIQUE DE DONNÉES                                  │
│                                                                                     │
│  Légende : (1,n) = One to Many    (0,n) = Zero to Many    (1,1) = One to One        │
└─────────────────────────────────────────────────────────────────────────────────────┘

═══════════════════════════════════════════════════════════════════════════════════════
                                    GAMME
═══════════════════════════════════════════════════════════════════════════════════════

GAMME (id, nom, slug, description, image_cover, ordre_affichage, timestamps)
   │
   │ 1,n
   │
   ├──────────────────────────────────────────────────────────────────────────────┐
   │                                                                              │
   ▼                                                                              ▼
OUVRAGE (id, reference, nom, slug, gamme_id, categorie_id,                      COMPOSANT (id, reference, designation, slug, 
         description_courte, description_technique,                              type_composant_id, gamme_id, matiere,
         largeur_min_mm, largeur_max_mm, hauteur_min_mm,                         longueur_barre_mm, poids_lineaire_kg_m,
         hauteur_max_mm, performance_thermique,                                  section_largeur_mm, section_hauteur_mm,
         performance_acoustique, image_principale,                               epaisseur_paroi_mm, moment_inertie_x_cm4,
         est_actif, timestamps)                                                  moment_inertie_y_cm4, module_elasticite_x_cm3,
   │                                                                             module_elasticite_y_cm3, image_coupe,
   │                                                                             est_disponible, timestamps)
   │ 1                                                                             │
   │                                                                               │ 1
   │                                                                               │
   │ 0,n                                                                           │ 0,n
   │                                                                               │
   ▼                                                                               ▼

═══════════════════════════════════════════════════════════════════════════════════════
                                  CATEGORIE
═══════════════════════════════════════════════════════════════════════════════════════

CATEGORIE (id, nom, slug, description, icone, timestamps)
   │
   │ 1,n
   │
   ▼
OUVRAGE (voir ci-dessus)

═══════════════════════════════════════════════════════════════════════════════════════
                              TYPE_COMPOSANT
═══════════════════════════════════════════════════════════════════════════════════════

TYPE_COMPOSANT (id, nom, slug, description, timestamps)
   │
   │ 1,n
   │
   ▼
COMPOSANT (voir ci-dessus)

═══════════════════════════════════════════════════════════════════════════════════════
                         COMPOSITION_OUVRAGE (Liaison)
═══════════════════════════════════════════════════════════════════════════════════════

OUVRAGE (1,n) ──── COMPOSITION_OUVRAGE (ouvrage_id, composant_id, quantite, unite,
                                        ordre, longueur_coupe_mm, commentaire,
                                        timestamps) ──── (1,n) COMPOSANT

═══════════════════════════════════════════════════════════════════════════════════════
                                  FINITION
═══════════════════════════════════════════════════════════════════════════════════════

COMPOSANT (0,n) ──── COMPOSANT_FINITION (composant_id, finition_id, est_par_defaut,
                                         timestamps) ──── (0,n) FINITION (id, nom, slug,
                                                                          code_ral,
                                                                          type_finition,
                                                                          description,
                                                                          timestamps)

═══════════════════════════════════════════════════════════════════════════════════════
                           CARACTERISTIQUE (EAV - Polymorphique)
═══════════════════════════════════════════════════════════════════════════════════════

COMPOSANT (1,n) ──── CARACTERISTIQUE (id, caracterisable_id, caracterisable_type,
                                      cle, valeur, unite, ordre_affichage,
                                      timestamps) ──── (1,n) OUVRAGE

═══════════════════════════════════════════════════════════════════════════════════════
                              MEDIA (Polymorphique)
═══════════════════════════════════════════════════════════════════════════════════════

MEDIA (id, chemin_fichier, titre, description, type_media, est_principal, timestamps)
   │
   │
   │  MEDIA_MORPH (media_id, mediable_id, mediable_type, ordre, timestamps)
   │
   ▼
   (0,n) GAMME / CATEGORIE / OUVRAGE / COMPOSANT (via polymorphique)

═══════════════════════════════════════════════════════════════════════════════════════
                              DOCUMENT
═══════════════════════════════════════════════════════════════════════════════════════

DOCUMENT (id, chemin_fichier, titre, description, type_document, taille_octets,
          timestamps)
   │
   │  DOCUMENT_ASSOCIATION (document_id, ouvrage_id, composant_id, timestamps)
   │
   ▼
   (0,n) OUVRAGE / COMPOSANT






GAMME (1) ────────── (0,n) OUVRAGE
GAMME (1) ────────── (0,n) COMPOSANT

CATEGORIE (1) ────── (0,n) OUVRAGE

TYPE_COMPOSANT (1) ─ (0,n) COMPOSANT

OUVRAGE (1) ──────── (1,n) COMPOSITION_OUVRAGE ──────── (1,n) COMPOSANT (1)
OUVRAGE (1) ──────── (1,n) CARACTERISTIQUE (polymorphique)
OUVRAGE (1) ──────── (0,n) DOCUMENT (via DOCUMENT_ASSOCIATION)
OUVRAGE (1) ──────── (0,n) MEDIA (via MEDIA_MORPH)

COMPOSANT (1) ────── (1,n) COMPOSITION_OUVRAGE ──────── (1,n) OUVRAGE (1)
COMPOSANT (1) ────── (0,n) COMPOSANT_FINITION ──────── (0,n) FINITION (0,n)
COMPOSANT (1) ────── (1,n) CARACTERISTIQUE (polymorphique)
COMPOSANT (1) ────── (0,n) DOCUMENT (via DOCUMENT_ASSOCIATION)
COMPOSANT (1) ────── (0,n) MEDIA (via MEDIA_MORPH)

MEDIA (0,n) ──────── (0,n) GAMME / CATEGORIE / OUVRAGE / COMPOSANT

DOCUMENT (0,n) ───── (0,n) OUVRAGE / COMPOSANT



