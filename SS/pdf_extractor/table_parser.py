# scripts/pdf_extractor/table_parser.py

import re
import cv2
import numpy as np
import easyocr

class TableParser:
    def __init__(self):
        # Patterns pour détecter les colonnes
        self.column_headers = {
            'reference': ['REF.', 'REF', 'REFERENCE'],
            'wt_ft': ['WT/FT', 'WT', 'LBS/FT'],
            'kg_m': ['KG/M', 'KG', 'KGM'],
            'inertie': ['IN', 'I'],
            'perimetre': ['PERIM.', 'PER', 'PERIM'],
        }

    def detect_table_structure(self, text):
        """Détecte la structure du tableau"""
        lines = text.split('\n')
        
        # Trouver la ligne d'en-tête
        header_line = None
        header_index = -1
        
        for i, line in enumerate(lines):
            line_upper = line.upper()
            if 'REF' in line_upper and ('WT/FT' in line_upper or 'KG/M' in line_upper or 'IN' in line_upper):
                header_line = line
                header_index = i
                break
        
        if header_line is None:
            return None
        
        # Détecter la position des colonnes
        columns = {}
        for col_name, headers in self.column_headers.items():
            for header in headers:
                pos = header_line.upper().find(header)
                if pos != -1:
                    columns[col_name] = {
                        'start': pos,
                        'end': pos + len(header),
                        'header': header,
                    }
                    break
        
        return {
            'header_line': header_line,
            'header_index': header_index,
            'columns': columns,
            'lines': lines,
        }

    def parse_table_data(self, text, image_path=None):
        """Parse les données du tableau"""
        structure = self.detect_table_structure(text)
        if structure is None:
            # Essayer un parsing plus flexible
            return self.parse_fallback(text)
        
        lines = structure['lines']
        header_index = structure['header_index']
        columns = structure['columns']
        
        data = []
        current_entry = {}
        current_ref = None
        
        for i in range(header_index + 1, len(lines)):
            line = lines[i].strip()
            if not line:
                continue
            
            # Extraire la référence
            ref_match = re.search(r'^([A-Z]{2,4}-\d{3,4})|(ALN-\d{3,4})|(STC\d{3,4})', line)
            
            if ref_match:
                if current_ref and current_entry:
                    data.append(current_entry)
                
                current_ref = ref_match.group(0)
                current_entry = {
                    'reference': current_ref,
                    'wt_ft': None,
                    'kg_m': None,
                    'inertie': None,
                    'perimetre': None,
                }
                
                self._extract_values_from_line(line, columns, current_entry)
            
            elif current_ref:
                self._extract_values_from_line(line, columns, current_entry)
        
        if current_ref and current_entry:
            data.append(current_entry)
        
        return data

    def parse_fallback(self, text):
        """Parsing de fallback plus flexible"""
        lines = text.split('\n')
        data = []
        current_ref = None
        current_entry = {}
        
        for line in lines:
            line = line.strip()
            if not line:
                continue
            
            # Chercher une référence
            ref_match = re.search(r'([A-Z]{2,4}-\d{3,4})|(ALN-\d{3,4})|(STC\d{3,4})', line)
            
            if ref_match:
                if current_ref and current_entry:
                    data.append(current_entry)
                
                current_ref = ref_match.group(0)
                current_entry = {
                    'reference': current_ref,
                    'wt_ft': None,
                    'kg_m': None,
                    'inertie': None,
                    'perimetre': None,
                }
                
                # Extraire les données de la même ligne
                numbers = re.findall(r'(\d+[,.]?\d*)', line)
                if len(numbers) >= 2:
                    current_entry['wt_ft'] = numbers[0].replace(',', '.')
                    current_entry['inertie'] = numbers[1].replace(',', '.')
            
            elif current_ref:
                # Chercher KG/M et PERIM
                if 'KG/M' in line.upper() or 'KG' in line.upper():
                    num = re.search(r'(\d+[,.]?\d*)', line)
                    if num:
                        current_entry['kg_m'] = num.group(1).replace(',', '.')
                if 'PERIM' in line.upper() or 'PER.' in line.upper():
                    num = re.search(r'(\d+[,.]?\d*)', line)
                    if num:
                        current_entry['perimetre'] = num.group(1).replace(',', '.')
                # Si pas de mot-clé, essayer d'extraire des nombres
                elif not current_entry['kg_m'] or not current_entry['perimetre']:
                    numbers = re.findall(r'(\d+[,.]?\d*)', line)
                    if len(numbers) >= 1:
                        if not current_entry['kg_m']:
                            current_entry['kg_m'] = numbers[0].replace(',', '.')
                        elif not current_entry['perimetre'] and len(numbers) >= 2:
                            current_entry['perimetre'] = numbers[1].replace(',', '.')
        
        if current_ref and current_entry:
            data.append(current_entry)
        
        return data

    def _extract_values_from_line(self, line, columns, entry):
        """Extrait les valeurs d'une ligne"""
        line_upper = line.upper()
        
        for col_name, col_info in columns.items():
            if entry.get(col_name) is not None:
                continue
            
            start = col_info['start']
            end = col_info['end']
            
            if start < len(line):
                segment = line[start:min(end + 30, len(line))].strip()
                num_match = re.search(r'(\d+[,.]?\d*)', segment)
                if num_match:
                    value = num_match.group(1).replace(',', '.')
                    entry[col_name] = value
            
            # Fallback
            if entry.get(col_name) is None:
                for header in self.column_headers.get(col_name, []):
                    if header in line_upper:
                        num_match = re.search(r'(\d+[,.]?\d*)', line)
                        if num_match:
                            value = num_match.group(1).replace(',', '.')
                            entry[col_name] = value
                            break