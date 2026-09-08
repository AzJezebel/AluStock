# scripts/pdf_extractor/page_analyzer.py

import re
import cv2
import numpy as np

class PageAnalyzer:
    def __init__(self):
        # Motifs pour ignorer les éléments non pertinents
        self.ignore_patterns = [
            r'ALUMINA',
            r'FECHA:',
            r'Hoja No:',
            r'www\.',
            r'\.com',
            r'\.co',
        ]
        
        # Motifs pour détecter le type de page
        self.schema_patterns = [
            r'DETALLE:',
            r'ALZADA',
            r'HOJA DE PERFILES',
        ]
        
        self.table_patterns = [
            r'REF\.',
            r'PESO KG/m',
            r'WT/FT',
            r'PERIM\.',
            r'IN',
        ]

    def clean_text(self, text):
        """Nettoie le texte en ignorant les éléments non pertinents"""
        lines = text.split('\n')
        cleaned = []
        for line in lines:
            # Ignorer les lignes qui correspondent aux motifs
            if not any(re.search(pattern, line, re.IGNORECASE) for pattern in self.ignore_patterns):
                cleaned.append(line)
        return '\n'.join(cleaned)

    def detect_page_type(self, text):
        """Détecte le type de page"""
        text_upper = text.upper()
        
        has_schema = any(re.search(pattern, text_upper, re.IGNORECASE) for pattern in self.schema_patterns)
        has_table = any(re.search(pattern, text_upper, re.IGNORECASE) for pattern in self.table_patterns)
        
        if has_schema and has_table:
            return 'schema_with_table'
        elif has_schema:
            return 'schema_only'
        elif has_table:
            return 'table_only'
        else:
            return 'unknown'

    def detect_ouvrage_title(self, text):
        """Extrait le titre de l'ouvrage"""
        lines = text.split('\n')
        for line in lines:
            line = line.strip()
            # Chercher les titres en majuscules (ex: "PUERTA BATIENTE RANCH")
            if len(line) > 10 and line.isupper() and len(line.split()) > 1:
                # Vérifier que ce n'est pas une référence
                if not re.search(r'[A-Z]{2,4}-\d{3,4}', line):
                    return line
            # Chercher les lignes avec "DETALLE:"
            if 'DETALLE:' in line.upper():
                parts = line.split(':')
                if len(parts) > 1:
                    return parts[1].strip()
        return None

    def detect_ouvrage_id(self, text):
        """Extrait un ID potentiel pour l'ouvrage (ex: U-107)"""
        # Chercher des motifs comme U-107, F-006, etc.
        match = re.search(r'([A-Z]-\d{3,4})', text)
        if match:
            return match.group(1)
        return None