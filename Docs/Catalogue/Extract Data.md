Table Families (ex: Portes Battantes, Garde Corps, Barandillas)

id, name, slug

Table Profiles (ex: Lama Ranch, Marco, Angulo Union)

id, family_id, ref (ex: ALN-988), name, weight_per_meter (float), perimeter (float)

width (float), height (float), thickness (float) issu des cotes

Table Product_Attributes (Pour gérer les spécificités techniques)

id, profile_id, key (ex: "Forme", "Type de finition"), value

Table Media (Pour les images)

id, profile_id, type (schema_technical), url, alt

????


Outil gratuit : Tabula (open-source). Vous importez votre PDF, vous "dessinez" un rectangle autour des tableaux (Réf, Poids, PER), et il exporte un CSV/Excel propre. C'est très efficace pour ce type de mise en page.

Outils IA/Cloud : AWS Textract, Google Cloud Vision (via leur API de détection de tableaux) ou Adobe Acrobat Pro (fonction "Reconnaître le texte" + "Exporter vers Excel"). Ces outils sont très performants pour ce type de tableaux standards.



Les cotes (ex: 78.8, 46.4) sont des éléments graphiques qui sont à côté des dessins.

Solution simple : Une fois que votre OCR a extrait "LAMA RANCH" et le tableau, utilisez la position (coordonnées Y) du titre pour capturer le texte situé juste au-dessus de lui (entre le titre et le haut de la page). Les cotes sont souvent alignées horizontalement ou verticalement avec des lignes de rappel. L'OCR les lira simplement comme du texte.

Solution avancée (si vous voulez les associer aux bons côtés du profil) : Utilisez une librairie comme OpenCV pour détecter les lignes de cotation et les flèches. C'est très complexe. Je vous conseille fortement de récupérer uniquement les chiffres bruts et de les attribuer manuellement via une interface de validation.










py ????
Bibliothèques à utiliser : Camelot ou Tabula-py (pour extraire les tableaux), pdfplumber (pour extraire les textes et cotes).

Workflow :

Parcourir les 73 pages.
Extraire les tableaux sous forme de DataFrames (Pandas).
Fusionner toutes les données dans un seul fichier Excel/CSV.
Extraire les noms des profils (ex: "LAMA RANCH", "MARCO") en lisant le texte au-dessus des tableaux.
Optionnel (si vous voulez les cotes) : Utiliser OpenCV pour lire les lignes de cotation, mais c'est beaucoup plus complexe.


┌────────────────────────────────────────────────────────────────────────────┐
│                         STRATÉGIE D'EXTRACTION                             │
├────────────────────────────────────────────────────────────────────────────┤
│                                                                            │
│  ÉTAPE 1 : PRÉPARATION DES PDF                                             │
│  ┌─────────────────────────────────────────────────────────────────────┐   │
│  │ 1. Convertir PDF → Images (300 DPI)                                 │   │
│  │ 2. Rotation/correction des pages (si besoin)                        │   │
│  │ 3. Détection automatique du type de page (plan vs tableau)          │   │
│  └─────────────────────────────────────────────────────────────────────┘   │
│                                    │                                       │
│                                    ▼                                       │
│  ÉTAPE 2 : OCR                                                             │
│  ┌─────────────────────────────────────────────────────────────────────┐   │
│  │ Option A : Tesseract OCR (gratuit, open-source)                     │   │
│  │ Option B : Google Cloud Vision / AWS Textract (payant, plus précis) │   │
│  │ Option C : EasyOCR (bon pour texte multilingue)                     │   │
│  └─────────────────────────────────────────────────────────────────────┘   │
│                                    │                                       │
│                                    ▼                                       │
│  ÉTAPE 3 : POST-TRAITEMENT                                                 │
│  ┌─────────────────────────────────────────────────────────────────────┐   │
│  │ 1. Nettoyage du texte (supprimer les bruits)                        │   │
│  │ 2. Détection des motifs (références, poids, dimensions)             │   │
│  │ 3. Extraction par expressions régulières                            │   │
│  │ 4. Structuration en tableau (CSV / JSON)                            │   │
│  └─────────────────────────────────────────────────────────────────────┘   │
│                                    │                                       │
│                                    ▼                                       │
│  ÉTAPE 4 : VALIDATION ET CORRECTION                                        │
│  ┌─────────────────────────────────────────────────────────────────────┐   │
│  │ 1. Vérification des doublons                                        │   │
│  │ 2. Validation des formats (références, poids, etc.)                 │   │
│  │ 3. Export CSV / JSON                                                │   │
│  └─────────────────────────────────────────────────────────────────────┘   │
│                                    │                                       │
│                                    ▼                                       │
│  ÉTAPE 5 : IMPORT DANS LARAVEL                                             │
│  ┌─────────────────────────────────────────────────────────────────────┐   │
│  │ Option A : Commande artisan personnalisée (php artisan import:pdf)  │   │
│  │ Option B : Import CSV via seeder                                    │   │
│  │ Option C : Utilisation de Laravel Excel                             │   │
│  └─────────────────────────────────────────────────────────────────────┘   │
└────────────────────────────────────────────────────────────────────────────┘

# PDF & Image Processing
pdf2image==1.16.3
opencv-python==4.8.1.78
Pillow==10.1.0

# OCR
pytesseract==0.3.10
easyocr==1.7.0

# Data Processing
pandas==2.1.0
numpy==1.25.2
openpyxl==3.1.2

# Utilities
python-dotenv==1.0.0
tqdm==4.66.1

scripts/
├── pdf_extractor/
│   ├── __init__.py
│   ├── config.py              # Configuration (chemins, patterns)
│   ├── pdf_processor.py       # Conversion PDF → images
│   ├── ocr_engine.py          # OCR (Tesseract / EasyOCR)
│   ├── data_extractor.py      # Extraction des données
│   ├── data_cleaner.py        # Nettoyage et validation
│   ├── data_exporter.py       # Export CSV / JSON
│   └── main.py                # Point d'entrée
├── requirements.txt
├── input/                     # Dossier des PDF sources
├── output/                    # Dossier des résultats
│   ├── images/                # Images extraites
│   └── data/                  # CSV / JSON
└── run.py                     # Script d'exécution

scripts/
├── pdf_extractor/
│   ├── __init__.py
│   ├── config.py                 # Patterns + détection de type
│   ├── pdf_processor.py          # PDF → images + prétraitement
│   ├── table_detector.py         # Détection de tableaux
│   ├── ocr_engine.py             # OCR (EasyOCR)
│   ├── data_extractor.py         # Extraction par zone + post-traitement
│   ├── data_cleaner.py           # Nettoyage et validation
│   ├── data_exporter.py          # Export CSV/JSON/PHP
│   └── main.py
├── input/                        # 73 fichiers PDF ici
├── output/                       # Images et données extraites
└── run.py