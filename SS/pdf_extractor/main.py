#!/usr/bin/env python3
# scripts/pdf_extractor/main.py

import os
import sys
import io
import re
import pandas as pd
from datetime import datetime
from PIL import Image
import fitz
import easyocr
from .table_processor import TableProcessor
from .table_parser import TableParser


def convert_pdf_to_images(pdf_path, output_dir, dpi=250):
    """Convertit un PDF en images"""
    try:
        doc = fitz.open(pdf_path)
        images = []
        pdf_name = os.path.splitext(os.path.basename(pdf_path))[0]
        
        for page_num in range(len(doc)):
            page = doc[page_num]
            zoom = dpi / 72
            mat = fitz.Matrix(zoom, zoom)
            pix = page.get_pixmap(matrix=mat)
            
            img_data = pix.tobytes("png")
            img = Image.open(io.BytesIO(img_data))
            
            filename = f"{pdf_name}_page_{page_num+1:03d}.png"
            filepath = os.path.join(output_dir, filename)
            img.save(filepath, 'PNG')
            images.append(filepath)
        
        doc.close()
        return images
    except Exception as e:
        print(f"❌ Erreur conversion: {e}")
        return []

def ocr_image(image_path):
    """OCR sur une image"""
    try:
        reader = easyocr.Reader(['en', 'fr', 'es'], gpu=False, verbose=False)
        result = reader.readtext(image_path, detail=0)
        return ' '.join(result)
    except Exception as e:
        print(f"⚠️ Erreur OCR: {e}")
        return ""

def extract_title(text):
    """Extrait le titre de l'ouvrage"""
    lines = text.split('\n')
    for line in lines:
        line = line.strip()
        if len(line) > 10 and line.isupper() and len(line.split()) >= 2:
            if not re.search(r'[A-Z]{2,4}-\d{3,4}', line):
                return line
        if 'DETALLE:' in line.upper():
            parts = line.split(':')
            if len(parts) > 1:
                return parts[1].strip()
    return None

def main():
    print("🔍 AluStock - Extracteur structurel")
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
    table_processor = TableProcessor()
    table_parser = TableParser()
    
    all_pieces = []
    all_ouvrages = []
    all_schemas = []
    
    for pdf_file in pdf_files:
        print(f"\n📄 Traitement de: {pdf_file}")
        pdf_path = os.path.join(INPUT_DIR, pdf_file)
        
        # 1. Conversion en images
        print("   📸 Conversion en images...")
        images = convert_pdf_to_images(pdf_path, OUTPUT_IMAGES_DIR, dpi=250)
        print(f"   ✅ {len(images)} images générées")
        
        for img_idx, image_path in enumerate(images, 1):
            page_name = f"{os.path.splitext(pdf_file)[0]}_page_{img_idx:03d}"
            print(f"\n   🖼️ Traitement: {page_name}")
            
            # 2. Détection du tableau
            print("      📊 Détection du tableau...")
            table_region = table_processor.detect_table_region(image_path)
            
            if table_region is None:
                print("      ⚠️ Aucun tableau détecté")
                continue
            
            print(f"      📊 Tableau détecté: {table_region['width']}x{table_region['height']}px")
            
            # 3. Détection du schéma
            print("      🖼️ Détection du schéma...")
            schema_region = table_processor.detect_schema_region(image_path, table_region)
            
            if schema_region:
                schema_img = table_processor.extract_roi(image_path, schema_region)
                if schema_img:
                    schema_path = table_processor.save_schema(schema_img, page_name, OUTPUT_DATA_DIR)
                    all_schemas.append({
                        'page': page_name,
                        'schema_path': schema_path,
                        'region': schema_region,
                    })
                    print(f"      ✅ Schéma sauvegardé: {os.path.basename(schema_path)}")
            else:
                print("      ⚠️ Aucun schéma détecté")
            
            # 4. OCR du tableau
            print("      🔤 OCR du tableau...")
            table_img = table_processor.extract_roi(image_path, table_region)
            if table_img is None:
                continue
            
            # Sauvegarder le tableau en image (pour debug)
            table_dir = os.path.join(OUTPUT_DATA_DIR, 'tables')
            os.makedirs(table_dir, exist_ok=True)
            table_path = os.path.join(table_dir, f"{page_name}_table.png")
            table_img.save(table_path, 'PNG')
            
            # OCR sur le tableau
            text = ocr_image(table_path)
            
            if not text or len(text) < 20:
                print("      ⚠️ OCR échoué ou texte trop court")
                continue
            
            # 5. Parsing du tableau
            print("      📋 Parsing des données...")
            parsed_data = table_parser.parse_table_data(text, image_path)
            
            if parsed_data:
                # Extraire le titre
                full_text = ocr_image(image_path)
                titre = extract_title(full_text)
                
                if titre:
                    ouvrage_id = re.sub(r'[^a-zA-Z0-9]+', '_', titre[:30]).strip('_')
                    all_ouvrages.append({
                        'id': ouvrage_id,
                        'titre': titre,
                        'slug': re.sub(r'[^a-z0-9]+', '-', titre.lower()).strip('-'),
                        'page': page_name,
                        'schema_path': schema_path if schema_region else None,
                    })
                else:
                    ouvrage_id = f"PAGE_{img_idx:03d}"
                
                print(f"      📋 {len(parsed_data)} pièces extraites")
                for item in parsed_data:
                    item['ouvrage_id'] = ouvrage_id
                    item['page'] = page_name
                    item['schema_path'] = schema_path if schema_region else None
                    all_pieces.append(item)
                
                # Afficher un aperçu
                for item in parsed_data[:3]:
                    print(f"         - {item.get('reference', 'N/A')}: KG/M={item.get('kg_m', '—')}, WT/FT={item.get('wt_ft', '—')}, IN={item.get('inertie', '—')}, PERIM={item.get('perimetre', '—')}")
            else:
                print("      ⚠️ Aucune donnée parsée")
    
    # 6. Export
    print("\n💾 Export des données...")
    
    if all_ouvrages:
        df_ouvrages = pd.DataFrame(all_ouvrages)
        df_ouvrages.to_csv(os.path.join(OUTPUT_DATA_DIR, 'ouvrages.csv'), 
                          index=False, encoding='utf-8-sig')
        print(f"   ✅ {len(all_ouvrages)} ouvrages exportés")
    
    if all_pieces:
        df_pieces = pd.DataFrame(all_pieces)
        df_pieces.to_csv(os.path.join(OUTPUT_DATA_DIR, 'pieces.csv'), 
                        index=False, encoding='utf-8-sig')
        print(f"   ✅ {len(all_pieces)} pièces exportées")
    
    if all_schemas:
        df_schemas = pd.DataFrame(all_schemas)
        df_schemas.to_csv(os.path.join(OUTPUT_DATA_DIR, 'schemas.csv'), 
                         index=False, encoding='utf-8-sig')
        print(f"   ✅ {len(all_schemas)} schémas exportés")
    
    # 7. Aperçu final
    print("\n📋 Aperçu final:")
    print(f"   Ouvrages: {len(all_ouvrages)}")
    print(f"   Pièces: {len(all_pieces)}")
    print(f"   Schémas: {len(all_schemas)}")
    
    if all_pieces:
        print("\n   Exemple de données:")
        for p in all_pieces[:3]:
            ref = p.get('reference', 'N/A')
            kg = p.get('kg_m', '—')
            wt = p.get('wt_ft', '—')
            inert = p.get('inertie', '—')
            per = p.get('perimetre', '—')
            print(f"      - {ref}: KG/M={kg}, WT/FT={wt}, IN={inert}, PERIM={per}")
    
    print(f"\n✅ Terminé ! Données dans: {OUTPUT_DATA_DIR}")

if __name__ == '__main__':
    main()