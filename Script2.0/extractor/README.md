# Extraction du catalogue PDF scanné

## Installation

```bash
pip install -r requirements.txt
```

Il faut aussi le binaire **Tesseract OCR** installé sur le système (le paquet Python `pytesseract` n'est qu'un wrapper) :

```bash
# Ubuntu/Debian
sudo apt install tesseract-ocr tesseract-ocr-fra

# macOS
brew install tesseract tesseract-lang

# Windows : installeur officiel https://github.com/UB-Mannheim/tesseract/wiki
```

`PyMuPDF` (le module `fitz`) n'a pas besoin de `poppler` : la conversion PDF → image est autonome.

## Usage

```bash
python catalogue_extractor.py --input ./pdfs --out ./out --dpi 300 --lang eng --debug
```

- `--input` : un PDF unique ou un dossier contenant plusieurs PDF
- `--dpi` : 300 est un bon compromis qualité OCR / vitesse. Monte à 400 si les scans sont petits/flous
- `--lang` : langue Tesseract (`eng` si les en-têtes sont en anglais comme dans ton cas, `fra+eng` si le reste de la page mélange les deux)
- `--debug` : génère `out/debug/*_debug.png` avec les boîtes détectées dessinées dessus (vert = bloc schéma, rouge = tableau) — **indispensable la première fois** pour vérifier que la détection correspond bien à tes scans réels avant de lancer sur tout le catalogue

## Sortie

```
out/
  blocs/           <pdf>_pXXX_profilYY_bloc.png     -> schéma (avec ou sans tableau imbriqué)
  tableaux/        <pdf>_pXXX_profilYY_tableau.png  -> tableau seul, pour audit
  texte_brut/      <pdf>_pXXX_brut.txt              -> OCR complet de la page (traçabilité)
  debug/           <pdf>_pXXX_debug.png             -> boîtes de détection visualisées
  catalogue.csv    toutes les lignes extraites
  a_verifier.csv   sous-ensemble des lignes signalées douteuses par les contrôles automatiques
```

Colonnes du CSV : `pdf_source, page, profil_index, reference, kg_m, in, perim, texte_ligne_brut, schema_separe, image_bloc, image_tableau, texte_page_brut, a_verifier`.

`texte_ligne_brut` conserve la ligne OCR complète non découpée en colonnes : c'est ton filet de sécurité si le découpage colonne par colonne se trompe — tu peux toujours relire/corriger à partir de ce texte brut sans revenir à l'image.

## Calibration — à faire avant de lancer sur tout le catalogue

Lance d'abord sur **une seule page représentative** avec `--debug`, puis ouvre l'image dans `out/debug/` :

1. **Le rectangle rouge (tableau) ne couvre pas bien le tableau réel** → ajuste `GRID_LINE_KERNEL_RATIO` dans le script (plus petit = détecte des traits plus courts, utile si les tableaux sont petits ou proches du bord de page).
2. **Le rectangle vert (bloc) est trop petit et coupe le schéma** → augmente `BLOCK_DILATE_KERNEL` (ex : `(60, 60)`).
3. **Le rectangle vert englobe deux profilés voisins en un seul bloc** → réduis `BLOCK_DILATE_KERNEL`.
4. **Aucun tableau détecté du tout** → vérifie le DPI (300 minimum), et si les bordures du tableau sont très fines/claires sur le scan, réduis le paramètre `C` dans `_binarize()` (actuellement 15) pour un seuillage plus permissif.

## Sur la reconstruction des colonnes (OCR structuré)

Le script cherche d'abord la ligne d'en-tête (REFERENCE / KG/M / IN / PERIM, avec tolérance floue à l'OCR), note la position X de chaque en-tête, puis assigne chaque mot des lignes suivantes à la colonne dont l'en-tête est le plus proche horizontalement. C'est robuste tant que les colonnes sont alignées verticalement (cas normal d'un tableau à bordures), mais reste heuristique :

- Si un en-tête n'est identifié dans aucune ligne, **toute la page bascule en mode "texte brut only"** (`a_verifier=True` partout) plutôt que de produire un découpage silencieusement faux.
- Chaque ligne est aussi validée a posteriori (référence non vide, colonnes numériques qui ressemblent à des nombres) — sinon `a_verifier=True`.
- Travaille sur `a_verifier.csv` en priorité : c'est un sous-ensemble, pas tout le catalogue, donc la relecture manuelle reste gérable même sur un gros PDF.

## Limites connues / pistes d'amélioration

- Pas de correction de biais (deskew) : si les scans sont légèrement inclinés, la détection de lignes de grille peut être moins fiable. Si besoin, un recalage par transformée de Hough avant l'étape 2 peut être ajouté.
- Le tri "ordre de lecture" (haut→bas, gauche→droite) suppose une mise en page en grille assez régulière ; sur une page très irrégulière, vérifie l'ordre des `profil_index` dans le CSV.
- Le script ne différencie pas encore automatiquement plusieurs sous-tableaux collés verticalement dans un même cadre (rare mais possible sur certains catalogues) — à surveiller sur `a_verifier.csv`.
