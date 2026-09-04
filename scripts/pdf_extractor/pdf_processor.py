# scripts/pdf_extractor/pdf_processor.py (version PyMuPDF)

import os
import fitz  # PyMuPDF
from PIL import Image
import cv2
import io
import numpy as np
from tqdm import tqdm

class PDFProcessor:
    def __init__(self, input_dir='input', output_dir='output/images', dpi=300):
        self.input_dir = input_dir
        self.output_dir = output_dir
        self.dpi = dpi
        os.makedirs(output_dir, exist_ok=True)

    def convert_pdf_to_images(self, pdf_path):
        """Convertit un PDF en images avec PyMuPDF (pas besoin de Poppler)"""
        try:
            doc = fitz.open(pdf_path)
            images = []
            for page_num in range(len(doc)):
                page = doc[page_num]
                zoom = self.dpi / 72
                mat = fitz.Matrix(zoom, zoom)
                pix = page.get_pixmap(matrix=mat)
                
                # Convertir en PIL Image
                img_data = pix.tobytes("png")
                img = Image.open(io.BytesIO(img_data))
                images.append(img)
            doc.close()
            return images
        except Exception as e:
            print(f"Erreur lors de la conversion de {pdf_path}: {e}")
            return []

    def save_image(self, image, page_num, pdf_name):
        filename = f"{pdf_name}_page_{page_num:03d}.png"
        filepath = os.path.join(self.output_dir, filename)
        image.save(filepath, 'PNG')
        return filepath

    def preprocess_image(self, image):
        """Prétraitement pour améliorer l'OCR"""
        # Convertir en numpy array
        img_array = np.array(image)
        
        # Convertir en niveaux de gris
        if len(img_array.shape) == 3:
            gray = cv2.cvtColor(img_array, cv2.COLOR_RGB2GRAY)
        else:
            gray = img_array
        
        # Binarisation (seuillage adaptatif)
        binary = cv2.adaptiveThreshold(
            gray, 255, 
            cv2.ADAPTIVE_THRESH_GAUSSIAN_C, 
            cv2.THRESH_BINARY, 11, 2
        )
        
        # Nettoyage (débruitage)
        denoised = cv2.medianBlur(binary, 3)
        
        return denoised

    def process_all_pdfs(self):
        results = []
        pdf_files = [f for f in os.listdir(self.input_dir) if f.endswith('.pdf')]
        
        for pdf_file in tqdm(pdf_files, desc="Conversion PDF"):
            pdf_path = os.path.join(self.input_dir, pdf_file)
            pdf_name = os.path.splitext(pdf_file)[0]
            
            images = self.convert_pdf_to_images(pdf_path)
            
            for i, image in enumerate(images, 1):
                img_path = self.save_image(image, i, pdf_name)
                results.append({
                    'pdf_name': pdf_name,
                    'page': i,
                    'image_path': img_path,
                })
                
        return results

