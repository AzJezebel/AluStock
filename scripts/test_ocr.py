# test_ocr.py

import sys
sys.path.insert(0, 'scripts')

from pdf_extractor.ocr_engine import OCREngine
import os

# Vérifier que le fichier existe
image_path = 'output/images/test_image.png'  # Mets un chemin valide

if not os.path.exists(image_path):
    print(f"❌ Image non trouvée: {image_path}")
    print("Utilise plutôt une image existante dans output/images/")
    # Lister les images disponibles
    images_dir = 'output/images'
    if os.path.exists(images_dir):
        images = [f for f in os.listdir(images_dir) if f.endswith('.png')]
        if images:
            image_path = os.path.join(images_dir, images[0])
            print(f"📸 Utilisation de: {image_path}")
        else:
            print("❌ Aucune image trouvée")
            exit()

# Test OCR
print("🔤 Test OCR...")
ocr = OCREngine(languages=['fr', 'en'], gpu=False)
text = ocr.extract_text(image_path)

print(f"\n📝 Texte extrait ({len(text)} caractères):")
print("-" * 60)
print(text[:500])  # Affiche les 500 premiers caractères
if len(text) > 500:
    print("...")
print("-" * 60)