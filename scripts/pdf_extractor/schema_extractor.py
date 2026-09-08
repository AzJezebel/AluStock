# scripts/pdf_extractor/schema_extractor.py

import cv2
import numpy as np
from PIL import Image
import os

class SchemaExtractor:
    def __init__(self):
        self.min_schema_area = 50000  # Surface minimum pour un schéma

    def detect_schema_region(self, image_path):
        """Détecte la région du schéma dans l'image"""
        # Charger l'image
        img = cv2.imread(image_path, cv2.IMREAD_GRAYSCALE)
        if img is None:
            return None
        
        h, w = img.shape
        
        # Binarisation
        _, binary = cv2.threshold(img, 150, 255, cv2.THRESH_BINARY_INV)
        
        # Détection des contours
        contours, _ = cv2.findContours(binary, cv2.RETR_EXTERNAL, cv2.CHAIN_APPROX_SIMPLE)
        
        # Filtrer les contours pour trouver le schéma
        schema_candidates = []
        for cnt in contours:
            x, y, cw, ch = cv2.boundingRect(cnt)
            area = cw * ch
            
            # Un schéma occupe généralement une grande partie de la page
            if area > self.min_schema_area and area < w * h * 0.8:
                # Vérifier que c'est un rectangle significatif
                if cw > w * 0.2 and ch > h * 0.2:
                    schema_candidates.append({
                        'x': x,
                        'y': y,
                        'width': cw,
                        'height': ch,
                        'area': area,
                    })
        
        # Garder le plus grand candidat
        if schema_candidates:
            return max(schema_candidates, key=lambda x: x['area'])
        return None

    def extract_schema(self, image_path, output_dir):
        """Extrait le schéma et le sauvegarde"""
        schema_region = self.detect_schema_region(image_path)
        if not schema_region:
            return None
        
        img = Image.open(image_path)
        x = schema_region['x']
        y = schema_region['y']
        w = schema_region['width']
        h = schema_region['height']
        
        # Extraire le schéma
        schema_img = img.crop((x, y, x + w, y + h))
        
        return schema_img, schema_region

    def save_schema(self, schema_img, ouvrage_id, output_dir):
        """Sauvegarde le schéma en image"""
        schemas_dir = os.path.join(output_dir, 'schemas')
        os.makedirs(schemas_dir, exist_ok=True)
        
        filename = f"{ouvrage_id}_schema.png"
        filepath = os.path.join(schemas_dir, filename)
        schema_img.save(filepath, 'PNG')
        return filepath