# scripts/pdf_extractor/ocr_engine.py

import os
import cv2
import easyocr
import numpy as np
from PIL import Image
from .config import ZONES

class OCREngine:
    def __init__(self, languages=['fr', 'es', 'en'], gpu=False):
        self.reader = easyocr.Reader(languages, gpu=gpu)
        self.zones = ZONES

    def crop_zone(self, image_path, zone_key):
        """Découpe une zone spécifique de l'image"""
        img = Image.open(image_path)
        w, h = img.size
        
        zone = self.zones.get(zone_key)
        if not zone:
            return None
        
        x1 = int(zone['x'][0] * w)
        x2 = int(zone['x'][1] * w)
        y1 = int(zone['y'][0] * h)
        y2 = int(zone['y'][1] * h)
        
        cropped = img.crop((x1, y1, x2, y2))
        return cropped

    def extract_from_zone(self, image_path, zone_key):
        """Extrait le texte d'une zone spécifique"""
        cropped = self.crop_zone(image_path, zone_key)
        if cropped is None:
            return ""
        
        # Sauvegarde temporaire
        temp_path = "temp_crop.png"
        cropped.save(temp_path)
        
        # OCR
        result = self.reader.readtext(temp_path, detail=0)
        os.remove(temp_path)
        
        return ' '.join(result)

    def extract_full_text(self, image_path):
        """Extraction du texte complet de l'image"""
        result = self.reader.readtext(image_path, detail=0)
        return ' '.join(result)

    def extract_structured(self, image_path):
        """Extraction structurée par zones"""
        return {
            'titre': self.extract_from_zone(image_path, 'titre'),
            'tableau': self.extract_from_zone(image_path, 'tableau'),
            'references': self.extract_from_zone(image_path, 'references'),
            'poids': self.extract_from_zone(image_path, 'poids'),
            'dimensions': self.extract_from_zone(image_path, 'dimensions'),
            'full_text': self.extract_full_text(image_path),
        }
