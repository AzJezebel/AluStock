# scripts/pdf_extractor/config.py

import re

# Patterns d'extraction (étendus pour couvrir les variations)
PATTERNS = {
    # Références : ALN-XXX, STCXXX, U-XXX, F-XXX
    'reference': re.compile(
        r'\b(?:ALN-\d{3,4}|STC\d{2,3}|U-\d{3,4}|F-\d{3,4}|[A-Z]{2,4}-\d{3,4})\b'
    ),
    
    # Poids (KG/m) – avec virgule ou point
    'poids_kg_m': re.compile(
        r'(\d{1,3}(?:[.,]\d{1,3})?)\s*(?:KG/M|kg/m)',
        re.IGNORECASE
    ),
    
    # Poids (WT/FT) – format anglo-saxon
    'wt_ft': re.compile(
        r'(\d{1,3}(?:[.,]\d{1,3})?)\s*(?:WT/FT)',
        re.IGNORECASE
    ),
    
    # Périmètre (PER / PERIM.)
    'perimetre': re.compile(
        r'(\d{1,3}(?:[.,]\d{1,3})?)\s*(?:PER|PERIM\.)',
        re.IGNORECASE
    ),
    
    # Dimensions (mm)
    'dimensions': re.compile(
        r'(\d{1,4}(?:[.,]\d{1,2})?)\s*(?:mm|MM)',
        re.IGNORECASE
    ),
    
    # Désignations (titres en majuscules)
    'designation': re.compile(
        r'^([A-Z\s\-ÀÂÇÉÈÊËÎÏÔÛÙÜŸÑ]{3,})$'
    ),
}

# Types de page
PAGE_TYPES = {
    'PLAN': 'plan_technique',        # Pages avec schémas (ex: PUERTA BATIENTE RANCH)
    'CATALOGUE': 'catalogue',        # Pages avec tableaux REF/WT/FT/IN
    'UNKNOWN': 'unknown',
}

# Zones d'intérêt (coordonnées approximatives en % de la page)
# Ces valeurs peuvent être ajustées après analyse des premières pages
ZONES = {
    'tableau': {'x': (0.05, 0.95), 'y': (0.15, 0.85)},   # Zone des tableaux
    'titre': {'x': (0.05, 0.95), 'y': (0.02, 0.12)},      # Zone du titre
    'references': {'x': (0.05, 0.40), 'y': (0.15, 0.85)}, # Zone des références
    'poids': {'x': (0.40, 0.70), 'y': (0.15, 0.85)},      # Zone des poids
    'dimensions': {'x': (0.70, 0.95), 'y': (0.15, 0.85)}, # Zone des dimensions
}

# Seuils de confiance
MIN_OCR_CONFIDENCE = 0.3
MIN_REFERENCE_LENGTH = 4
