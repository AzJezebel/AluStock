#!/usr/bin/env python3
# run.py

import os
import sys

# Ajouter le dossier scripts au path
sys.path.insert(0, os.path.join(os.path.dirname(__file__), 'scripts'))

from pdf_extractor.main import main

if __name__ == '__main__':
    main()
