# scripts/pdf_extractor/data_cleaner.py

import re

class DataCleaner:
    def __init__(self):
        self.reference_pattern = re.compile(r'^[A-Z]{2,4}-\d{3,4}$|^ALN-\d{3,4}$|^STC\d{3}$|^U-\d{3,4}$|^F-\d{3,4}$')
        self.float_pattern = re.compile(r'^(\d+[,.]?\d*)$')

    def clean_reference(self, ref):
        if not ref:
            return None
        cleaned = re.sub(r'[^\w-]', '', str(ref).strip())
        if self.reference_pattern.match(cleaned):
            return cleaned
        # Tentative de correction
        if len(cleaned) >= 4:
            return cleaned
        return None

    def clean_float(self, value):
        if not value:
            return None
        cleaned = str(value).replace(',', '.').strip()
        if self.float_pattern.match(cleaned):
            try:
                return float(cleaned)
            except:
                return None
        return None

    def clean_text(self, text):
        if not text:
            return ""
        cleaned = re.sub(r'[^\w\s\-.,:]', '', text)
        cleaned = re.sub(r'\s+', ' ', cleaned)
        return cleaned.strip()

    def validate_entry(self, entry):
        required_fields = ['reference']
        for field in required_fields:
            if not entry.get(field):
                return False
        return True
