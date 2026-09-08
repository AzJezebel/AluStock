# TOP SCREEN 
Nom ouvrage (ventana de celosia - portail double battant) To do : Automate translate
OU
Nom profilé (Perfiles varios) To add : Composants->Profiles Composants->Pieces 
OU
No header just schema/legende


# Main
Schema profiles qui composent l'ouvrage (ouvrages/{slug-id}/composition Voir toutes les pieces)
Tableau lié au schema avec WT/FT, KG/M, IN, PERIM.
Il faudrait que le script donne un id au scan du schema et le meme id au tableau de details lui correspondant
Le script devrait extraire un rectangle/carre contenant le schema en tant quimage et les data du tableau de specs en text


Ignorer le logo ALUMINA les mentions 'Fecha', 'Hoja no'
'Detalle' si present devrait etre lie a l'id de louvrage


┌─────────────────────────────────────────────────────────────────────────────┐
│                    NOUVEAU PROCESSUS                                        │
├─────────────────────────────────────────────────────────────────────────────┤ 
│                                                                             │
│  📄 PDF (scanné)                                                            │
│       │                                                                     │
│       ▼                                                                     │
│  ┌─────────────────────────────────────────────────────────────────────┐    │
│  │  ÉTAPE 1 : pdf_processor.py                                         │    │
│  │  → Convertit le PDF en images                                       │    │
│  └─────────────────────────────────────────────────────────────────────┘    │
│       │                                                                     │
│       ▼                                                                     │
│  ┌─────────────────────────────────────────────────────────────────────┐    │
│  │  ÉTAPE 2 : page_analyzer.py (NOUVEAU)                               │    │
│  │  → Détecte le type de page :                                        │    │
│  │    - Page avec schéma + tableau                                     │    │
│  │    - Page avec seulement tableau                                    │    │
│  │    - Page avec seulement schéma                                     │    │
│  │  → Ignore le logo (ALUMINA) et les mentions (Fecha, Hoja)           │    │
│  └─────────────────────────────────────────────────────────────────────┘    │
│       │                                                                     │
│       ▼                                                                     │
│  ┌─────────────────────────────────────────────────────────────────────┐    │
│  │  ÉTAPE 3 : schema_extractor.py (NOUVEAU)                            │    │
│  │  → Détecte le rectangle du schéma                                   │    │
│  │  → Extrait le schéma en image                                       │    │
│  │  → Extrait le titre (nom de l'ouvrage)                              │    │
│  │  → Génère un ID unique pour l'ouvrage                               │    │
│  └─────────────────────────────────────────────────────────────────────┘    │
│       │                                                                     │
│       ▼                                                                     │
│  ┌─────────────────────────────────────────────────────────────────────┐    │
│  │  ÉTAPE 4 : table_extractor.py (NOUVEAU)                             │    │
│  │  → Détecte le tableau de specs                                      │    │
│  │  → Extrait les données : REF, WT/FT, KG/M, IN, PERIM.               │    │
│  │  → Lie les données au même ID que le schéma                         │    │
│  └─────────────────────────────────────────────────────────────────────┘    │
│       │                                                                     │
│       ▼                                                                     │
│  ┌─────────────────────────────────────────────────────────────────────┐    │
│  │  ÉTAPE 5 : data_exporter.py                                         │    │
│  │  → Export des ouvrages (id, titre, image_schema)                    │    │
│  │  → Export des pièces (reference, poids, inertie, perimetre)         │    │
│  │  → Export des compositions (ouvrage_id, piece_id, quantite)         │    │
│  └─────────────────────────────────────────────────────────────────────┘    │
│       │                                                                     │
│       ▼                                                                     │
│   output/data/                                                              │
│     ├── ouvrages.csv                                                        │
│     ├── pieces.csv                                                          │
│     ├── compositions.csv                                                    │
│     ├── schemas/                                                            │
│     │   ├── ouvrage_001_schema.png                                          │
│     │   └── ...                                                             │
│     └── ImportAluData.php (Seeder Laravel)                                  │
└─────────────────────────────────────────────────────────────────────────────┘