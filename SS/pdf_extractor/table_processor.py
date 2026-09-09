# scripts/pdf_extractor/table_processor.py

import cv2
import numpy as np
from PIL import Image
import os
import re

class TableProcessor:
    def __init__(self):
        # Seuils ajustables
        self.min_table_height = 80
        self.min_table_width = 150
        self.line_thickness = 1
        self.horizontal_kernel_size = (40, 1)
        self.vertical_kernel_size = (1, 40)
        
        # Seuils de détection
        self.line_threshold = 150
        self.min_line_length = 50

    def detect_lines(self, image_path):
        """Détecte les lignes horizontales et verticales"""
        try:
            img = cv2.imread(image_path, cv2.IMREAD_GRAYSCALE)
            if img is None:
                return None, None
            
            h, w = img.shape
            
            # Binarisation adaptative (meilleure pour les scans)
            binary = cv2.adaptiveThreshold(img, 255, cv2.ADAPTIVE_THRESH_GAUSSIAN_C, 
                                          cv2.THRESH_BINARY_INV, 11, 2)
            
            # Détection des lignes horizontales
            horizontal_kernel = cv2.getStructuringElement(cv2.MORPH_RECT, self.horizontal_kernel_size)
            horizontal_lines = cv2.morphologyEx(binary, cv2.MORPH_OPEN, horizontal_kernel)
            
            # Détection des lignes verticales
            vertical_kernel = cv2.getStructuringElement(cv2.MORPH_RECT, self.vertical_kernel_size)
            vertical_lines = cv2.morphologyEx(binary, cv2.MORPH_OPEN, vertical_kernel)
            
            return horizontal_lines, vertical_lines
        except Exception as e:
            print(f"      ⚠️ Erreur détection lignes: {e}")
            return None, None

    def detect_table_by_content(self, image_path):
        """Détecte le tableau par recherche de texte (fallback)"""
        try:
            import easyocr
            reader = easyocr.Reader(['en', 'fr', 'es'], gpu=False, verbose=False)
            result = reader.readtext(image_path, detail=1)
            
            # Chercher les mots-clés du tableau
            keywords = ['REF', 'WT/FT', 'KG/M', 'IN', 'PERIM', 'REF.', 'WT']
            table_bbox = None
            
            for item in result:
                text = item[1].upper()
                bbox = item[0]
                
                if any(kw in text for kw in keywords):
                    # Calculer la boîte englobante
                    x_coords = [p[0] for p in bbox]
                    y_coords = [p[1] for p in bbox]
                    
                    if table_bbox is None:
                        table_bbox = {
                            'x_min': min(x_coords),
                            'x_max': max(x_coords),
                            'y_min': min(y_coords),
                            'y_max': max(y_coords),
                        }
                    else:
                        table_bbox['x_min'] = min(table_bbox['x_min'], min(x_coords))
                        table_bbox['x_max'] = max(table_bbox['x_max'], max(x_coords))
                        table_bbox['y_min'] = min(table_bbox['y_min'], min(y_coords))
                        table_bbox['y_max'] = max(table_bbox['y_max'], max(y_coords))
            
            if table_bbox:
                # Ajouter une marge
                margin = 20
                return {
                    'x': max(0, int(table_bbox['x_min']) - margin),
                    'y': max(0, int(table_bbox['y_min']) - margin),
                    'width': int(table_bbox['x_max'] - table_bbox['x_min']) + margin * 2,
                    'height': int(table_bbox['y_max'] - table_bbox['y_min']) + margin * 2,
                    'x_max': int(table_bbox['x_max']) + margin,
                    'y_max': int(table_bbox['y_max']) + margin,
                }
            return None
        except Exception as e:
            print(f"      ⚠️ Erreur détection par contenu: {e}")
            return None

    def detect_table_region(self, image_path):
        """Détecte la région du tableau"""
        # Essayer la détection par lignes d'abord
        h_lines, v_lines = self.detect_lines(image_path)
        
        if h_lines is not None and v_lines is not None:
            h_line_coords = np.where(h_lines > 0)
            v_line_coords = np.where(v_lines > 0)
            
            if len(h_line_coords[0]) > 10 and len(v_line_coords[0]) > 10:
                y_min = np.min(h_line_coords[0])
                y_max = np.max(h_line_coords[0])
                x_min = np.min(v_line_coords[1])
                x_max = np.max(v_line_coords[1])
                
                # Ajouter une marge
                margin = 30
                h, w = cv2.imread(image_path, cv2.IMREAD_GRAYSCALE).shape
                
                return {
                    'x': max(0, x_min - margin),
                    'y': max(0, y_min - margin),
                    'width': min(w, x_max - x_min + margin * 2),
                    'height': min(h, y_max - y_min + margin * 2),
                    'x_max': min(w, x_max + margin),
                    'y_max': min(h, y_max + margin),
                }
        
        # Fallback : détection par contenu texte
        print("      ⚠️ Fallback: détection par contenu texte...")
        return self.detect_table_by_content(image_path)

    def detect_schema_region(self, image_path, table_region):
        """Détecte la région du schéma (au-dessus ou à côté du tableau)"""
        try:
            img = cv2.imread(image_path, cv2.IMREAD_GRAYSCALE)
            if img is None:
                return None
            
            h, w = img.shape
            table_y = table_region['y']
            table_x = table_region['x']
            
            # La zone du schéma est au-dessus du tableau
            schema_y_max = table_y - 10
            
            if schema_y_max > 100:
                # Chercher la limite supérieure du contenu
                top_section = img[0:schema_y_max, 0:w]
                _, binary_top = cv2.threshold(top_section, 150, 255, cv2.THRESH_BINARY_INV)
                
                row_sums = np.sum(binary_top > 0, axis=1)
                content_start = 0
                for i, row_sum in enumerate(row_sums):
                    if row_sum > w * 0.03:
                        content_start = i
                        break
                
                # Si le schéma est à gauche du tableau
                if table_x > 200:
                    schema_x_max = table_x - 10
                    if schema_x_max > 100:
                        return {
                            'x': 20,
                            'y': content_start - 10,
                            'width': schema_x_max - 20,
                            'height': schema_y_max - content_start + 10,
                            'x_max': schema_x_max,
                            'y_max': schema_y_max,
                        }
                
                # Sinon, le schéma est au-dessus
                return {
                    'x': 20,
                    'y': content_start - 10,
                    'width': w - 40,
                    'height': schema_y_max - content_start + 10,
                    'x_max': w - 20,
                    'y_max': schema_y_max,
                }
            
            return None
        except Exception as e:
            print(f"      ⚠️ Erreur détection schéma: {e}")
            return None

    def extract_roi(self, image_path, region):
        """Extrait une région d'intérêt de l'image"""
        if region is None:
            return None
        
        try:
            img = Image.open(image_path)
            x = region['x']
            y = region['y']
            w = region['width']
            h = region['height']
            
            # S'assurer que les coordonnées sont valides
            x = max(0, min(x, img.width - 10))
            y = max(0, min(y, img.height - 10))
            w = min(img.width - x, w)
            h = min(img.height - y, h)
            
            if w < 20 or h < 20:
                return None
            
            roi = img.crop((x, y, x + w, y + h))
            return roi
        except Exception as e:
            print(f"      ⚠️ Erreur extraction ROI: {e}")
            return None

    def save_schema(self, schema_img, page_name, output_dir):
        """Sauvegarde le schéma"""
        schemas_dir = os.path.join(output_dir, 'schemas')
        os.makedirs(schemas_dir, exist_ok=True)
        
        filename = f"{page_name}_schema.png"
        filepath = os.path.join(schemas_dir, filename)
        schema_img.save(filepath, 'PNG')
        return filepath