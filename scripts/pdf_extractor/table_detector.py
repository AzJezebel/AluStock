# scripts/pdf_extractor/table_detector.py

import cv2
import numpy as np
from PIL import Image

class TableDetector:
    def __init__(self):
        self.debug = False

    def detect_lines(self, image_path):
        """Détecte les lignes horizontales et verticales"""
        img = cv2.imread(image_path, cv2.IMREAD_GRAYSCALE)
        if img is None:
            return None, None
        
        # Binarisation
        _, binary = cv2.threshold(img, 150, 255, cv2.THRESH_BINARY_INV)
        
        # Détection des lignes horizontales
        horizontal_kernel = cv2.getStructuringElement(cv2.MORPH_RECT, (40, 1))
        horizontal_lines = cv2.morphologyEx(binary, cv2.MORPH_OPEN, horizontal_kernel)
        
        # Détection des lignes verticales
        vertical_kernel = cv2.getStructuringElement(cv2.MORPH_RECT, (1, 40))
        vertical_lines = cv2.morphologyEx(binary, cv2.MORPH_OPEN, vertical_kernel)
        
        return horizontal_lines, vertical_lines

    def detect_table_regions(self, image_path):
        """Détecte les régions de tableau"""
        img = cv2.imread(image_path, cv2.IMREAD_GRAYSCALE)
        if img is None:
            return []
        
        # Détection des contours
        _, binary = cv2.threshold(img, 150, 255, cv2.THRESH_BINARY_INV)
        contours, _ = cv2.findContours(binary, cv2.RETR_EXTERNAL, cv2.CHAIN_APPROX_SIMPLE)
        
        table_regions = []
        h, w = img.shape
        
        for cnt in contours:
            x, y, cw, ch = cv2.boundingRect(cnt)
            # Filtrer les petits éléments
            if cw > w * 0.2 and ch > h * 0.05:
                table_regions.append({
                    'x': x / w,
                    'y': y / h,
                    'width': cw / w,
                    'height': ch / h,
                })
        
        return table_regions

    def is_table_page(self, image_path):
        """Détermine si une page contient un tableau structuré"""
        lines_h, lines_v = self.detect_lines(image_path)
        if lines_h is None or lines_v is None:
            return False
        
        # Compter les lignes
        h_count = np.sum(lines_h > 0)
        v_count = np.sum(lines_v > 0)
        
        # Une page avec tableau a généralement plusieurs lignes
        return h_count > 100 and v_count > 100
