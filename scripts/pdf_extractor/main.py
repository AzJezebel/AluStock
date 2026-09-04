#!/usr/bin/env python3
# scripts/pdf_extractor/main.py

import os
import sys
from tqdm import tqdm
from .pdf_processor import PDFProcessor
from .table_detector import TableDetector
from .ocr_engine import OCREngine
from .data_extractor import DataExtractor
from .data_cleaner import DataCleaner
from .data_exporter import DataExporter

def main():
    print("🔍 AluStock - Extracteur de données PDF (version structurée)")
    print("=" * 60)
    
    BASE_DIR = os.path.dirname(os.path.dirname(os.path.abspath(__file__)))
    INPUT_DIR = os.path.join(BASE_DIR, 'input')
    OUTPUT_IMAGES_DIR = os.path.join(BASE_DIR, 'output', 'images')
    OUTPUT_DATA_DIR = os.path.join(BASE_DIR, 'output', 'data')
    
    os.makedirs(INPUT_DIR, exist_ok=True)
    os.makedirs(OUTPUT_IMAGES_DIR, exist_ok=True)
    os.makedirs(OUTPUT_DATA_DIR, exist_ok=True)
    
    pdf_files = [f for f in os.listdir(INPUT_DIR) if f.endswith('.pdf')]
    print(f"📄 {len(pdf_files)} fichier(s) PDF trouvé(s)")
    
    if not pdf_files:
        print("❌ Aucun PDF dans le dossier 'input'")
        return
    
    # Initialisation
    processor = PDFProcessor(input_dir=INPUT_DIR, output_dir=OUTPUT_IMAGES_DIR, dpi=300)
    detector = TableDetector()
    ocr = OCREngine(languages=['fr', 'es', 'en'], gpu=False)
    extractor = DataExtractor()
    cleaner = DataCleaner()
    exporter = DataExporter(output_dir=OUTPUT_DATA_DIR)
    
    all_results = []
    images_data = processor.process_all_pdfs()
    
    for img_data in tqdm(images_data, desc="📖 Extraction en cours"):
        try:
            # Détection du type de page
            is_table = detector.is_table_page(img_data['image_path'])
            
            # OCR structuré
            structured_text = ocr.extract_structured(img_data['image_path'])
            
            # Extraction des données
            extracted = extractor.extract_all(structured_text)
            extracted['page_type'] = 'tableau' if is_table else 'plan'
            extracted['page'] = img_data['page']
            
            # Nettoyage
            for ref in extracted.get('references', []):
                cleaned_ref = cleaner.clean_reference(ref)
                if cleaned_ref:
                    all_results.append({
                        'reference': cleaned_ref,
                        'designations': extracted.get('designations', []),
                        'poids_kg_m': extracted.get('poids_kg_m', []),
                        'wt_ft': extracted.get('wt_ft', []),
                        'perimetre': extracted.get('perimetre', []),
                        'dimensions': extracted.get('dimensions', []),
                        'titre': extracted.get('titre', ''),
                        'type': extracted.get('type', 'unknown'),
                        'page': img_data['page'],
                    })
        
        except Exception as e:
            print(f"⚠️ Erreur sur {img_data['image_path']}: {e}")
    
    # Export
    if all_results:
        exporter.export_to_csv(all_results)
        exporter.export_to_laravel_seeder(all_results)
        print(f"\n✅ Extraction terminée ! {len(all_results)} éléments extraits.")
    else:
        print("⚠️ Aucune donnée extraite.")

if __name__ == '__main__':
    main()
