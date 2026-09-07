# #!/usr/bin/env python3
# # scripts/pdf_extractor/main.py

# import os
# import sys
# from tqdm import tqdm
# from .pdf_processor import PDFProcessor
# from .table_detector import TableDetector
# from .ocr_engine import OCREngine
# from .data_extractor import DataExtractor
# from .data_cleaner import DataCleaner
# from .data_exporter import DataExporter

# def main():
#     print("🔍 AluStock - Extracteur de données PDF (version structurée)")
#     print("=" * 60)
    
#     BASE_DIR = os.path.dirname(os.path.dirname(os.path.abspath(__file__)))
#     INPUT_DIR = os.path.join(BASE_DIR, 'input')
#     OUTPUT_IMAGES_DIR = os.path.join(BASE_DIR, 'output', 'images')
#     OUTPUT_DATA_DIR = os.path.join(BASE_DIR, 'output', 'data')
    
#     os.makedirs(INPUT_DIR, exist_ok=True)
#     os.makedirs(OUTPUT_IMAGES_DIR, exist_ok=True)
#     os.makedirs(OUTPUT_DATA_DIR, exist_ok=True)
    
#     pdf_files = [f for f in os.listdir(INPUT_DIR) if f.endswith('.pdf')]
#     print(f"📄 {len(pdf_files)} fichier(s) PDF trouvé(s)")
    
#     if not pdf_files:
#         print("❌ Aucun PDF dans le dossier 'input'")
#         return
    
#     # Initialisation
#     processor = PDFProcessor(input_dir=INPUT_DIR, output_dir=OUTPUT_IMAGES_DIR, dpi=300)
#     detector = TableDetector()
#     ocr = OCREngine(languages=['fr', 'es', 'en'], gpu=False)
#     extractor = DataExtractor()
#     cleaner = DataCleaner()
#     exporter = DataExporter(output_dir=OUTPUT_DATA_DIR)
    
#     all_results = []
#     images_data = processor.process_all_pdfs()
    
#     for img_data in tqdm(images_data, desc="📖 Extraction en cours"):
#         try:
#             # Détection du type de page
#             is_table = detector.is_table_page(img_data['image_path'])
            
#             # OCR structuré
#             structured_text = ocr.extract_structured(img_data['image_path'])
            
#             # Extraction des données
#             extracted = extractor.extract_all(structured_text)
#             extracted['page_type'] = 'tableau' if is_table else 'plan'
#             extracted['page'] = img_data['page']
            
#             # Nettoyage
#             for ref in extracted.get('references', []):
#                 cleaned_ref = cleaner.clean_reference(ref)
#                 if cleaned_ref:
#                     all_results.append({
#                         'reference': cleaned_ref,
#                         'designations': extracted.get('designations', []),
#                         'poids_kg_m': extracted.get('poids_kg_m', []),
#                         'wt_ft': extracted.get('wt_ft', []),
#                         'perimetre': extracted.get('perimetre', []),
#                         'dimensions': extracted.get('dimensions', []),
#                         'titre': extracted.get('titre', ''),
#                         'type': extracted.get('type', 'unknown'),
#                         'page': img_data['page'],
#                     })
        
#         except Exception as e:
#             print(f"⚠️ Erreur sur {img_data['image_path']}: {e}")
    
#     # Export
#     if all_results:
#         exporter.export_to_csv(all_results)
#         exporter.export_to_laravel_seeder(all_results)
#         print(f"\n✅ Extraction terminée ! {len(all_results)} éléments extraits.")
#     else:
#         print("⚠️ Aucune donnée extraite.")

# if __name__ == '__main__':
#     main()


#!/usr/bin/env python3
# scripts/pdf_extractor/main.py

import os
import sys
import json
import logging
from datetime import datetime
from tqdm import tqdm
from .pdf_processor import PDFProcessor
from .ocr_engine import OCREngine
from .data_extractor import DataExtractor
from .data_cleaner import DataCleaner
from .data_exporter import DataExporter

def setup_logging(output_dir):
    """Configure le système de logging"""
    log_dir = os.path.join(output_dir, 'logs')
    os.makedirs(log_dir, exist_ok=True)
    
    timestamp = datetime.now().strftime('%Y%m%d_%H%M%S')
    log_file = os.path.join(log_dir, f'extraction_{timestamp}.log')
    
    # Configuration du logging
    logging.basicConfig(
        level=logging.INFO,
        format='%(asctime)s - %(levelname)s - %(message)s',
        handlers=[
            logging.FileHandler(log_file, encoding='utf-8'),
            logging.StreamHandler()  # Affiche aussi dans la console
        ]
    )
    
    return log_file

def save_raw_text(raw_text, img_data, output_dir):
    """Sauvegarde le texte brut dans un fichier individuel"""
    raw_dir = os.path.join(output_dir, 'raw_texts')
    os.makedirs(raw_dir, exist_ok=True)
    
    filename = f"{img_data['pdf_name']}_page_{img_data['page']:03d}_raw.txt"
    filepath = os.path.join(raw_dir, filename)
    
    with open(filepath, 'w', encoding='utf-8') as f:
        f.write(f"=== SOURCE ===\n")
        f.write(f"PDF: {img_data['pdf_name']}.pdf\n")
        f.write(f"Page: {img_data['page']}\n")
        f.write(f"Image: {os.path.basename(img_data['image_path'])}\n")
        f.write(f"Date: {datetime.now().strftime('%Y-%m-%d %H:%M:%S')}\n")
        f.write(f"\n=== TEXTE BRUT OCR ===\n")
        f.write(f"{'=' * 60}\n\n")
        f.write(raw_text)
        f.write(f"\n\n=== STATISTIQUES ===\n")
        f.write(f"Nombre de caractères: {len(raw_text)}\n")
        f.write(f"Nombre de mots: {len(raw_text.split())}\n")
        f.write(f"Nombre de lignes: {len(raw_text.splitlines())}\n")
    
    return filepath

def append_to_master_log(raw_text, img_data, master_log_path):
    """Ajoute le texte brut au log master (un seul fichier)"""
    with open(master_log_path, 'a', encoding='utf-8') as f:
        f.write(f"\n{'=' * 80}\n")
        f.write(f"PDF: {img_data['pdf_name']}.pdf | Page: {img_data['page']}\n")
        f.write(f"{'=' * 80}\n")
        f.write(raw_text)
        f.write(f"\n\n")

def main():
    print("🔍 AluStock - Extracteur de données PDF")
    print("=" * 60)
    
    BASE_DIR = os.path.dirname(os.path.dirname(os.path.abspath(__file__)))
    INPUT_DIR = os.path.join(BASE_DIR, 'input')
    OUTPUT_IMAGES_DIR = os.path.join(BASE_DIR, 'output', 'images')
    OUTPUT_DATA_DIR = os.path.join(BASE_DIR, 'output', 'data')
    
    # Création des dossiers
    os.makedirs(INPUT_DIR, exist_ok=True)
    os.makedirs(OUTPUT_IMAGES_DIR, exist_ok=True)
    os.makedirs(OUTPUT_DATA_DIR, exist_ok=True)
    
    # Configuration du logging
    log_file = setup_logging(OUTPUT_DATA_DIR)
    logging.info(f"📝 Log file: {log_file}")
    logging.info(f"📄 Démarrage de l'extraction")
    
    pdf_files = [f for f in os.listdir(INPUT_DIR) if f.endswith('.pdf')]
    logging.info(f"📄 {len(pdf_files)} fichier(s) PDF trouvé(s)")
    
    if not pdf_files:
        logging.error("❌ Aucun PDF dans le dossier 'input'")
        return
    
    # 1. Conversion PDF → Images
    logging.info("📸 Étape 1: Conversion PDF en images...")
    processor = PDFProcessor(
        input_dir=INPUT_DIR, 
        output_dir=OUTPUT_IMAGES_DIR, 
        dpi=300
    )
    images_data = processor.process_all_pdfs()
    logging.info(f"   ✅ {len(images_data)} images générées")
    
    if not images_data:
        logging.error("❌ Aucune image générée. Vérifie les PDF.")
        return
    
    # 2. OCR
    logging.info("🔤 Étape 2: OCR des images...")
    ocr = OCREngine(languages=['fr', 'es', 'en', 'pt'], gpu=False)
    
    # Master log pour tous les textes bruts
    master_raw_log = os.path.join(OUTPUT_DATA_DIR, 'logs', 'all_raw_texts.txt')
    os.makedirs(os.path.dirname(master_raw_log), exist_ok=True)
    
    # Initialiser le master log
    with open(master_raw_log, 'w', encoding='utf-8') as f:
        f.write(f"{'=' * 80}\n")
        f.write(f"MASTER LOG - TEXTE BRUT OCR\n")
        f.write(f"Date: {datetime.now().strftime('%Y-%m-%d %H:%M:%S')}\n")
        f.write(f"{'=' * 80}\n\n")
        f.write(f"Total PDFs: {len(pdf_files)}\n")
        f.write(f"Total images: {len(images_data)}\n")
        f.write(f"\n{'=' * 80}\n\n")
    
    all_results = []
    raw_texts_summary = []
    
    for img_data in tqdm(images_data, desc="   OCR en cours"):
        try:
            logging.info(f"   📄 Traitement: {img_data['pdf_name']} - Page {img_data['page']}")
            
            # Extraction du texte
            text = ocr.extract_text(img_data['image_path'])
            
            # Sauvegarde du texte brut individuel
            raw_file = save_raw_text(text, img_data, OUTPUT_DATA_DIR)
            logging.info(f"      💾 Texte brut sauvegardé: {os.path.basename(raw_file)}")
            
            # Ajout au master log
            append_to_master_log(text, img_data, master_raw_log)
            
            # Sauvegarde du résumé
            raw_texts_summary.append({
                'pdf_name': img_data['pdf_name'],
                'page': img_data['page'],
                'char_count': len(text),
                'word_count': len(text.split()),
                'line_count': len(text.splitlines()),
                'file': raw_file,
            })
            
            # Extraction des données structurées
            extractor = DataExtractor()
            extracted = extractor.extract_all({'full_text': text})
            extracted['page'] = img_data['page']
            extracted['pdf_name'] = img_data['pdf_name']
            
            all_results.append(extracted)
            
        except Exception as e:
            logging.error(f"   ❌ Erreur sur {img_data['image_path']}: {e}")
            # Ajouter l'erreur au log
            with open(master_raw_log, 'a', encoding='utf-8') as f:
                f.write(f"\n!!! ERREUR sur {img_data['pdf_name']} - Page {img_data['page']} !!!\n")
                f.write(f"Erreur: {str(e)}\n\n")
    
    # 3. Export des données
    logging.info("💾 Étape 3: Export des données...")
    exporter = DataExporter(output_dir=OUTPUT_DATA_DIR)
    
    if all_results:
        # Export CSV
        csv_path = exporter.export_to_csv(all_results)
        logging.info(f"   📊 CSV: {csv_path}")
        
        # Export JSON
        json_path = exporter.export_to_json(all_results)
        logging.info(f"   📋 JSON: {json_path}")
        
        # Export Laravel Seeder
        php_path = exporter.export_to_laravel_seeder(all_results)
        logging.info(f"   🐘 Laravel Seeder: {php_path}")
        
        logging.info(f"\n✅ Extraction terminée !")
        logging.info(f"   📝 {len(all_results)} pages traitées")
        logging.info(f"   📄 Log complet: {master_raw_log}")
    else:
        logging.warning("⚠️ Aucune donnée extraite. Vérifie les images.")
    
    # 4. Afficher un résumé des textes extraits
    logging.info("\n📊 Résumé des textes bruts extraits:")
    for summary in raw_texts_summary[:5]:  # Affiche les 5 premiers
        logging.info(f"   📄 {summary['pdf_name']} - Page {summary['page']}: "
                    f"{summary['char_count']} caractères, "
                    f"{summary['word_count']} mots")
    
    if len(raw_texts_summary) > 5:
        logging.info(f"   ... et {len(raw_texts_summary) - 5} autres pages")
    
    logging.info(f"\n📁 Logs disponibles dans: {os.path.join(OUTPUT_DATA_DIR, 'logs')}")
    logging.info(f"📄 Master log: {master_raw_log}")
    
    # 5. Sauvegarder un résumé JSON des métadonnées
    summary_path = os.path.join(OUTPUT_DATA_DIR, 'logs', 'extraction_summary.json')
    with open(summary_path, 'w', encoding='utf-8') as f:
        json.dump({
            'timestamp': datetime.now().isoformat(),
            'total_pdfs': len(pdf_files),
            'total_images': len(images_data),
            'total_pages_with_data': len(all_results),
            'raw_texts': raw_texts_summary,
            'log_file': log_file,
            'master_log': master_raw_log,
        }, f, indent=2, ensure_ascii=False)
    logging.info(f"📋 Résumé sauvegardé: {summary_path}")

if __name__ == '__main__':
    main()