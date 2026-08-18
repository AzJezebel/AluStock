┌─────────────────────────────────────────────────────────────────────────────────────┐
│                     DIAGRAMME DE CLASSES - CATALOGUE TECHNIQUE                      │
└─────────────────────────────────────────────────────────────────────────────────────┘

┌─────────────────────┐          ┌─────────────────────┐          ┌─────────────────────┐
│       GAMME         │          │    TYPE_OUVRAGE     │          │     TYPE_PIECE      │
├─────────────────────┤          ├─────────────────────┤          ├─────────────────────┤
│ - id : INT          │          │ - id : INT          │          │ - id : INT          │
│ - nom : VARCHAR     │          │ - nom : VARCHAR     │          │ - nom : VARCHAR     │
│ - slug : VARCHAR    │          │ - slug : VARCHAR    │          │ - slug : VARCHAR    │
│ - description : TEXT│          │ - description : TEXT│          │ - description : TEXT│
│ - image_cover : STR │          │ - icone : VARCHAR   │          │ - timestamps        │
│ - ordre : INT       │          │ - timestamps        │          │                     │
│ - timestamps        │          │                     │          │                     │
└──────────┬──────────┘          └───────────┬─────────┘          └──────────┬──────────┘
           │ 1 (une gamme)                   │ 1 (un type)                   │ 1 (un type)
           │                                 │                               │
           │ 0..* (plusieurs modèles)        │ 0..* (plusieurs modèles)      │ 0..* (plusieurs pièces)
           │                                 │                               │
           ▼                                 ▼                               ▼
┌────────────────────────────────────────────────────────────────────────────────────┐
│                                     MODELE                                         │
├────────────────────────────────────────────────────────────────────────────────────┤
│ - id : INT                                                                         │
│ - reference : VARCHAR(50) UNIQUE                                                   │
│ - nom : VARCHAR(200)                                                               │
│ - slug : VARCHAR(220) UNIQUE                                                       │
│ - gamme_id : INT (FK)                                                              │
│ - type_ouvrage_id : INT (FK)                                                       │
│ - description_courte : TEXT                                                        │
│ - description_technique : LONGTEXT                                                 │
│ - largeur_min_mm : INT                                                             │
│ - largeur_max_mm : INT                                                             │
│ - hauteur_min_mm : INT                                                             │
│ - hauteur_max_mm : INT                                                             │
│ - performance_thermique : VARCHAR(50)                                              │
│ - performance_acoustique : VARCHAR(50)                                             │
│ - image_principale : VARCHAR(255)                                                  │
│ - est_actif : BOOLEAN                                                              │
│ - timestamps                                                                       │
└──────────┬─────────────────────────────────────────────────────┬───────────────────┘
           │ 1 (un modèle)                                       │ 0..* (une pièce
           │                                                     │  peut être dans
           │ 0..* (une pièce peut                                │  plusieurs modèles)
           │  être dans plusieurs modèles)                       │
           │                                                     │
           ▼                                                     ▼
┌─────────────────────────────┐                    ┌─────────────────────────────┐
│   COMPOSITION_MODELE        │                    │          PIECE              │
│  (Table d'association)      │                    ├─────────────────────────────┤
├─────────────────────────────┤                    │ - id : INT                  │
│ - modele_id : INT (FK)      │◄───────────────────│ - reference : VARCHAR(50)   │
│ - piece_id : INT (FK)       │                    │ - designation : VARCHAR(200)│
│ - quantite : DECIMAL(10,2)  │                    │ - slug : VARCHAR(220)       │
│ - unite : VARCHAR(20)       │                    │ - type_piece_id : INT (FK)  │
│ - ordre : INT               │                    │ - gamme_id : INT (FK)       │
│ - longueur_coupe_mm : INT   │                    │ - matiere : VARCHAR(100)    │
│ - commentaire : TEXT        │                    │ - longueur_barre_mm : INT   │
│ - timestamps                │                    │ - poids_lineaire : DECIMAL  │
└─────────────────────────────┘                    │ - section_largeur : DECIMAL │
                                                   │ - section_hauteur : DECIMAL │
                                                   │ - epaisseur_paroi : DECIMAL │
                                                   │ - inertie_x : DECIMAL       │
                                                   │ - inertie_y : DECIMAL       │
                                                   │ - module_x : DECIMAL        │
                                                   │ - module_y : DECIMAL        │
                                                   │ - image_coupe : VARCHAR     │
                                                   │ - est_disponible : BOOLEAN  │
                                                   │ - timestamps                │
                                                   └──────────────┬──────────────┘
                                                                  │ 1 (une pièce)
                                                                  │
                                                                  │ 0..* (plusieurs
                                                                  │ caractéristiques)
                                                                  ▼
                                                   ┌─────────────────────────────┐
                                                   │    CARACTERISTIQUE (EAV)    │
                                                   ├─────────────────────────────┤
                                                   │ - id : INT                  │
                                                   │ - piece_id : INT (FK)       │
                                                   │ - cle : VARCHAR(100)        │
                                                   │ - valeur : VARCHAR(255)     │
                                                   │ - unite : VARCHAR(20)       │
                                                   │ - ordre_affichage : INT     │
                                                   │ - timestamps                │
                                                   └─────────────────────────────┘


┌─────────────────────────────┐          ┌─────────────────────────────────────────────┐
│         FINITION            │          │       PIECE_FINITION                        │
├─────────────────────────────┤          │   (Table d'association)                     │
│ - id : INT                  │          ├─────────────────────────────────────────────┤
│ - nom : VARCHAR(100)        │◄─────────│ - piece_id : INT (FK)                       │
│ - slug : VARCHAR(120)       │          │ - finition_id : INT (FK)                    │
│ - code_ral : VARCHAR(10)    │          │ - est_par_defaut : BOOLEAN                  │
│ - type_finition : ENUM      │          │ - timestamps                                │
│ - description : TEXT        │          └─────────────────────────────────────────────┘
│ - timestamps                │
└─────────────────────────────┘


┌─────────────────────────────┐          ┌─────────────────────────────────────────────┐
│          MEDIA              │          │         MEDIA_MORPH                         │
├─────────────────────────────┤          │   (Table polymorphique)                     │
│ - id : INT                  │          ├─────────────────────────────────────────────┤
│ - chemin_fichier : VARCHAR  │◄─────────│ - media_id : INT (FK)                       │
│ - titre : VARCHAR(200)      │          │ - mediable_id : INT                         │
│ - description : TEXT        │          │ - mediable_type : VARCHAR(100)              │
│ - type_media : ENUM         │          │ - ordre : INT                               │
│ - est_principal : BOOLEAN   │          │ - timestamps                                │
│ - timestamps                │          └─────────────────────────────────────────────┘
└─────────────────────────────┘                         │
                                                        │
                                                        │  (GAMME, TYPE_OUVRAGE, MODELE, PIECE)
                                                        │  via mediable_type + mediable_id


┌─────────────────────────────┐          ┌─────────────────────────────────────────────┐
│         DOCUMENT            │          │      DOCUMENT_ASSOCIATION                   │
├─────────────────────────────┤          ├─────────────────────────────────────────────┤
│ - id : INT                  │◄─────────│ - document_id : INT (FK)                    │
│ - chemin_fichier : VARCHAR  │          │ - modele_id : INT (FK) NULL                 │
│ - titre : VARCHAR(200)      │          │ - piece_id : INT (FK) NULL                  │
│ - description : TEXT        │          │ - timestamps                                │
│ - type_document : ENUM      │          └─────────────────────────────────────────────┘
│ - taille_octets : INT       │
│ - timestamps                │
└─────────────────────────────┘

Relation	Cardinalité	Signification
GAMME → MODELE	(1) → (0,n)	Une gamme contient plusieurs modèles. Un modèle appartient à une gamme.
TYPE_OUVRAGE → MODELE	(1) → (0,n)	Un type regroupe plusieurs modèles. Un modèle est d'un seul type.
MODELE ↔ PIECE (via COMPOSITION)	(1,n) ↔ (1,n)	Un modèle est composé de plusieurs pièces. Une pièce peut être dans plusieurs modèles.
TYPE_PIECE → PIECE	(1) → (0,n)	Un type de pièce regroupe plusieurs pièces. Une pièce a un seul type.
PIECE ↔ FINITION (via PIECE_FINITION)	(0,n) ↔ (0,n)	Une pièce peut avoir plusieurs finitions. Une finition peut s'appliquer à plusieurs pièces.
PIECE → CARACTERISTIQUE	(1) → (0,n)	Une pièce a plusieurs caractéristiques. Une caractéristique appartient à une pièce.
MEDIA ↔ ENTITÉS (via MEDIA_MORPH)	(0,n) ↔ (0,n)	Un média peut être rattaché à plusieurs entités. Une entité peut avoir plusieurs médias.
DOCUMENT ↔ MODELE/PIECE (via DOCUMENT_ASSOCIATION)	(0,n) ↔ (0,n)	Un document peut être associé à plusieurs entités. Une entité peut avoir plusieurs documents.