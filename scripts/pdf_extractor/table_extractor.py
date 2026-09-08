# scripts/pdf_extractor/table_extractor.py

import re
import pandas as pd
from .config import PATTERNS

class TableExtractor:
    def __init__(self):
        self.patterns = PATTERNS

    def extract_table_data(self, text):
        """Extrait les données du tableau"""
        lines = text.split('\n')
        
        # Détecter les lignes qui contiennent des données de tableau
        table_lines = []
        in_table = False
        
        for line in lines:
            line = line.strip()
            
            # Détecter l'en-tête du tableau
            if 'REF.' in line and ('PESO KG/m' in line or 'WT/FT' in line):
                in_table = True
                continue
            
            # Détecter la fin du tableau
            if in_table and len(line) < 5:
                in_table = False
                continue
            
            if in_table and line:
                table_lines.append(line)
        
        # Extraire les données
        data = []
        current_ref = None
        
        for line in table_lines:
            # Chercher une référence
            ref_match = re.search(self.patterns['reference'], line)
            poids_match = re.search(self.patterns['poids_kg_m'], line)
            wt_ft_match = re.search(self.patterns['wt_ft'], line)
            perimetre_match = re.search(self.patterns['perimetre'], line)
            inertie_match = re.search(self.patterns['inertie_in4'], line)
            
            if ref_match:
                current_ref = ref_match.group(1)
                # Créer une nouvelle entrée
                entry = {
                    'reference': current_ref,
                    'poids_kg_m': poids_match.group(1).replace(',', '.') if poids_match else None,
                    'wt_ft': wt_ft_match.group(1).replace(',', '.') if wt_ft_match else None,
                    'perimetre': perimetre_match.group(1).replace(',', '.') if perimetre_match else None,
                    'inertie_in4': inertie_match.group(1).replace(',', '.') if inertie_match else None,
                }
                data.append(entry)
            elif current_ref and data:
                # Ajouter les données manquantes à la dernière entrée
                last_entry = data[-1]
                if poids_match and not last_entry['poids_kg_m']:
                    last_entry['poids_kg_m'] = poids_match.group(1).replace(',', '.')
                if wt_ft_match and not last_entry['wt_ft']:
                    last_entry['wt_ft'] = wt_ft_match.group(1).replace(',', '.')
                if perimetre_match and not last_entry['perimetre']:
                    last_entry['perimetre'] = perimetre_match.group(1).replace(',', '.')
                if inertie_match and not last_entry['inertie_in4']:
                    last_entry['inertie_in4'] = inertie_match.group(1).replace(',', '.')
        
        return data

    def extract_all_tables(self, pages_data):
        """Extrait les tableaux de toutes les pages"""
        all_data = []
        for page in pages_data:
            text = page.get('raw_text', '')
            table_data = self.extract_table_data(text)
            for item in table_data:
                item['ouvrage_id'] = page.get('ouvrage_id')
                item['page'] = page.get('page')
            all_data.extend(table_data)
        return all_data