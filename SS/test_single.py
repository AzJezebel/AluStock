# test_single.py

import sys
import os
sys.path.insert(0, 'scripts')

from pdf_extractor.table_processor import TableProcessor
from pdf_extractor.table_parser import TableParser
from pdf_extractor.main import convert_pdf_to_images, ocr_image

# Tester sur un fichier spécifique
pdf_path = 'input/Resumen perfiles y sistemas-16.pdf'
output_dir = 'output/images'
data_dir = 'output/data'

# Convertir
images = convert_pdf_to_images(pdf_path, output_dir, dpi=200)
print(f"Images: {len(images)}")

for img_path in images[:1]:  # Tester seulement la première page
    print(f"\nTraitement de: {img_path}")
    
    processor = TableProcessor()
    parser = TableParser()
    
    # Détection tableau
    table = processor.detect_table_region(img_path)
    print(f"Tableau: {table}")
    
    if table:
        table_img = processor.extract_roi(img_path, table)
        if table_img:
            table_img.save('test_table.png')
            print("Tableau sauvegardé: test_table.png")
            
            # OCR
            text = ocr_image('test_table.png')
            print(f"Texte OCR: {text[:200]}...")
            
            # Parsing
            data = parser.parse_table_data(text)
            print(f"Données extraites: {len(data)}")
            for item in data[:3]:
                print(f"  {item}")
    
    # Détection schéma
    schema = processor.detect_schema_region(img_path, table)
    print(f"Schéma: {schema}")
    
    if schema:
        schema_img = processor.extract_roi(img_path, schema)
        if schema_img:
            schema_img.save('test_schema.png')
            print("Schéma sauvegardé: test_schema.png")