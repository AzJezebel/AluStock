# scripts/pdf_extractor/data_exporter.py

import csv
import json
import os
import pandas as pd

class DataExporter:
    def __init__(self, output_dir='output/data'):
        self.output_dir = output_dir
        os.makedirs(output_dir, exist_ok=True)

    def export_to_csv(self, data, filename='extracted_data.csv'):
        filepath = os.path.join(self.output_dir, filename)
        
        rows = []
        for item in data:
            references = item.get('references', [])
            poids = item.get('poids_kg_m', [])
            wt_ft = item.get('wt_ft', [])
            perimetre = item.get('perimetre', [])
            
            # Créer une ligne par référence
            for i, ref in enumerate(references):
                rows.append({
                    'reference': ref,
                    'designation': item.get('designations', [''])[0] if item.get('designations') else '',
                    'poids_kg_m': poids[i] if i < len(poids) else '',
                    'wt_ft': wt_ft[i] if i < len(wt_ft) else '',
                    'perimetre': perimetre[i] if i < len(perimetre) else '',
                    'dimensions': ', '.join(item.get('dimensions', [])),
                    'titre': item.get('titre', ''),
                    'type': item.get('type', 'unknown'),
                })
        
        if rows:
            df = pd.DataFrame(rows)
            df.to_csv(filepath, index=False, encoding='utf-8-sig')
            print(f"✅ CSV exporté vers {filepath}")
        return filepath

    def export_to_laravel_seeder(self, data, filename='ImportComposants.php'):
        """Exporte les données au format Laravel Seeder"""
        filepath = os.path.join(self.output_dir, filename)
        
        php_code = "<?php\n\nnamespace Database\\Seeders;\n\nuse Illuminate\\Database\\Seeder;\nuse Illuminate\\Support\\Facades\\DB;\n\nclass ImportComposantsSeeder extends Seeder\n{\n"
        php_code += "    public function run(): void\n    {\n"
        
        for item in data:
            ref = item.get('reference', '')
            if not ref:
                continue
            php_code += "        DB::table('composants')->insert([\n"
            php_code += f"            'reference' => '{ref}',\n"
            php_code += f"            'designation' => '{item.get('designations', [''])[0] if item.get('designations') else ref}',\n"
            php_code += f"            'slug' => '{ref.lower()}',\n"
            php_code += f"            'type_composant_id' => 1,\n"
            php_code += f"            'matiere' => 'Aluminium',\n"
            php_code += f"            'poids_lineaire_kg_m' => {item.get('poids_kg_m', ['0'])[0] if item.get('poids_kg_m') else 0},\n"
            php_code += f"            'est_disponible' => true,\n"
            php_code += f"            'created_at' => now(),\n"
            php_code += f"            'updated_at' => now(),\n"
            php_code += "        ]);\n"
        
        php_code += "    }\n}\n"
        
        with open(filepath, 'w', encoding='utf-8') as f:
            f.write(php_code)
        print(f"✅ Seeder Laravel exporté vers {filepath}")
        return filepath
