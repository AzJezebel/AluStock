# scripts/pdf_extractor/data_extractor.py

import re
from .config import PATTERNS

class DataExtractor:
    def __init__(self):
        self.patterns = PATTERNS

    def extract_references(self, text):
        """Extrait toutes les références"""
        if not text:
            return []
        return list(set(re.findall(self.patterns['reference'], text)))

    def extract_poids_kg_m(self, text):
        """Extrait les poids (KG/m)"""
        if not text:
            return []
        matches = re.findall(self.patterns['poids_kg_m'], text)
        return [m.replace(',', '.') for m in matches]

    def extract_wt_ft(self, text):
        """Extrait les poids (WT/FT)"""
        if not text:
            return []
        matches = re.findall(self.patterns['wt_ft'], text)
        return [m.replace(',', '.') for m in matches]

    def extract_perimetre(self, text):
        """Extrait les périmètres"""
        if not text:
            return []
        matches = re.findall(self.patterns['perimetre'], text)
        return [m.replace(',', '.') for m in matches]

    def extract_dimensions(self, text):
        """Extrait les dimensions"""
        if not text:
            return []
        matches = re.findall(self.patterns['dimensions'], text)
        return [m.replace(',', '.') for m in matches]

    def extract_designations(self, text):
        """Extrait les désignations (titres)"""
        if not text:
            return []
        lines = text.split('\n')
        designations = []
        for line in lines:
            if len(line.strip()) > 3 and line.strip().isupper():
                match = re.match(self.patterns['designation'], line.strip())
                if match:
                    designations.append(match.group(1))
        return designations

    def extract_all(self, structured_text):
        """Extraction complète depuis le texte structuré"""
        full_text = structured_text.get('full_text', '')
        
        return {
            'titre': structured_text.get('titre', '').strip(),
            'references': self.extract_references(full_text),
            'poids_kg_m': self.extract_poids_kg_m(full_text),
            'wt_ft': self.extract_wt_ft(full_text),
            'perimetre': self.extract_perimetre(full_text),
            'dimensions': self.extract_dimensions(full_text),
            'designations': self.extract_designations(full_text),
            'type': self.detect_page_type(full_text),
        }

    def detect_page_type(self, text):
        """Détecte le type de page"""
        if 'PER' in text and 'PESO KG/m' in text:
            return 'plan_technique'
        elif 'REF' in text and 'WT/FT' in text and 'IN' in text:
            return 'catalogue'
        elif 'REF.' in text and 'PESO KG/m' in text:
            return 'tableau'
        elif 'DETALLE' in text or 'ALZADA' in text:
            return 'plan_technique'
        else:
            return 'unknown'
