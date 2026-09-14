#!/usr/bin/env python3
"""
catalogue_extractor.py

Pipeline d'extraction pour catalogue PDF scanné (images, pas de texte natif) :

  1. PDF -> images (par page, haute résolution)
  2. Détection des tableaux techniques (REFERENCE / KG/M / IN / PERIM) via les
     bordures du quadrillage (morphologie : lignes horizontales + verticales)
  3. Regroupement en "blocs profilé" : dilatation large de toute l'encre de la
     page pour fusionner schéma + tableau + libellés proches en un seul blob,
     puis rattachement de chaque tableau détecté au blob qui le contient.
     -> gère nativement le cas où le tableau est A L'INTERIEUR du schéma,
        et le cas où le schéma est une pièce séparée à côté.
  4. Export : crop du bloc complet (schéma, avec ou sans tableau imbriqué) +
     crop du tableau seul (pour un OCR plus propre).
  5. OCR structuré du tableau (repérage des en-têtes par position X, puis
     affectation des mots de chaque ligne à la colonne la plus proche).
  6. Texte brut OCR de la page entière conservé à part (traçabilité / audit).

Sortie (dans --out) :
    out/
      images_pages/        pages entières converties (debug/relecture)
      blocs/                <pdf>_pXX_profilYY_bloc.png   (schéma [+ tableau])
      tableaux/             <pdf>_pXX_profilYY_tableau.png (tableau seul)
      texte_brut/            <pdf>_pXX_brut.txt            (OCR page entière)
      debug/                 masques intermédiaires (si --debug)
      catalogue.csv          table de données structurée
      a_verifier.csv         sous-ensemble des lignes douteuses (relecture)

USAGE :
    pip install -r requirements.txt
    # + binaire système tesseract-ocr installé (voir README.md)

    python catalogue_extractor.py --input ./pdfs --out ./out --dpi 300 --debug
"""

from __future__ import annotations

import argparse
import csv
import difflib
import glob
import os
import re
from dataclasses import dataclass, field

import cv2
import fitz  # PyMuPDF
import numpy as np
import pytesseract
from pytesseract import Output

# ============================================================
# CONFIG — à ajuster selon le rendu réel de tes scans
# (utilise --debug pour visualiser les masques et calibrer)
# ============================================================

# En-têtes de colonnes attendues (variantes tolérées pour l'appariement flou,
# l'OCR d'un scan produit souvent des espaces ou caractères parasites)
EXPECTED_HEADERS = {
    "REFERENCE": ["reference", "référence", "ref", "réf"],
    "KG/M": ["kg/m", "kgm", "kg m", "kg"],
    "IN": ["in", "in2", "in²"],
    "PERIM": ["perim", "périm", "perimeter", "périmètre"],
}

# Tolérance d'appariement flou (0-1, plus haut = plus strict)
HEADER_MATCH_CUTOFF = 0.55

# Taille du noyau morphologique pour les lignes de grille du tableau,
# exprimée en fraction de la largeur/hauteur de la page.
GRID_LINE_KERNEL_RATIO = 1 / 40

# Aire minimale (en pixels²) pour qu'un contour de grille soit retenu
# comme candidat tableau (filtre le bruit / petits artefacts de scan)
MIN_TABLE_AREA_RATIO = 0.01  # 1% de la surface de la page

# Taille du noyau de dilatation utilisé pour regrouper schéma + tableau
# en un même "bloc profilé". Plus grand = fusionne des éléments plus éloignés.
BLOCK_DILATE_KERNEL = (45, 45)

# Marge de sécurité ajoutée autour d'un tableau isolé (aucun blob associé)
FALLBACK_MARGIN_RATIO = 0.03


# ============================================================
# ÉTAPE 1 — PDF -> images
# ============================================================

def pdf_to_cv_images(pdf_path: str, dpi: int = 300):
    """Rend chaque page du PDF en image OpenCV (BGR), sans dépendance à poppler."""
    doc = fitz.open(pdf_path)
    zoom = dpi / 72  # 72 dpi = résolution native PDF
    matrix = fitz.Matrix(zoom, zoom)

    pages = []
    for page_index in range(len(doc)):
        page = doc[page_index]
        pix = page.get_pixmap(matrix=matrix, colorspace=fitz.csRGB)
        img = np.frombuffer(pix.samples, dtype=np.uint8).reshape(pix.height, pix.width, pix.n)
        img_bgr = cv2.cvtColor(img, cv2.COLOR_RGB2BGR)
        pages.append((page_index + 1, img_bgr))
    doc.close()
    return pages


# ============================================================
# ÉTAPE 2 — Détection des tableaux (lignes de grille)
# ============================================================

def _binarize(gray: np.ndarray) -> np.ndarray:
    """Binarisation inversée : encre = blanc, fond = noir (attendu par les
    opérations morphologiques qui suivent)."""
    return cv2.adaptiveThreshold(
        gray, 255,
        cv2.ADAPTIVE_THRESH_MEAN_C, cv2.THRESH_BINARY_INV,
        blockSize=25, C=15
    )


def detect_table_boxes(gray: np.ndarray) -> list[tuple[int, int, int, int]]:
    """Détecte les rectangles de tableau via leurs lignes de grille.
    Retourne une liste de boîtes (x, y, w, h)."""
    h, w = gray.shape
    bin_img = _binarize(gray)

    horiz_len = max(10, int(w * GRID_LINE_KERNEL_RATIO))
    vert_len = max(10, int(h * GRID_LINE_KERNEL_RATIO))

    horiz_kernel = cv2.getStructuringElement(cv2.MORPH_RECT, (horiz_len, 1))
    vert_kernel = cv2.getStructuringElement(cv2.MORPH_RECT, (1, vert_len))

    horiz_lines = cv2.morphologyEx(bin_img, cv2.MORPH_OPEN, horiz_kernel, iterations=1)
    vert_lines = cv2.morphologyEx(bin_img, cv2.MORPH_OPEN, vert_kernel, iterations=1)

    grid_mask = cv2.bitwise_or(horiz_lines, vert_lines)
    # Referme les petites coupures de trait dues au bruit du scan
    grid_mask = cv2.dilate(grid_mask, np.ones((3, 3), np.uint8), iterations=2)

    contours, _ = cv2.findContours(grid_mask, cv2.RETR_EXTERNAL, cv2.CHAIN_APPROX_SIMPLE)

    min_area = w * h * MIN_TABLE_AREA_RATIO
    boxes = []
    for c in contours:
        x, y, cw, ch = cv2.boundingRect(c)
        if cw * ch < min_area:
            continue
        # Un tableau a des lignes ET des colonnes -> rejette les rectangles
        # beaucoup trop plats/étroits qui sont probablement de simples filets
        if cw < w * 0.05 or ch < h * 0.03:
            continue
        boxes.append((x, y, cw, ch))

    return _merge_overlapping_boxes(boxes)


def _merge_overlapping_boxes(boxes, iou_threshold: float = 0.15):
    """Fusionne les boîtes qui se chevauchent (les grilles imbriquées créent
    parfois plusieurs contours pour un même tableau)."""
    if not boxes:
        return []

    def iou(a, b):
        ax, ay, aw, ah = a
        bx, by, bw, bh = b
        ix1, iy1 = max(ax, bx), max(ay, by)
        ix2, iy2 = min(ax + aw, bx + bw), min(ay + ah, by + bh)
        iw, ih = max(0, ix2 - ix1), max(0, iy2 - iy1)
        inter = iw * ih
        union = aw * ah + bw * bh - inter
        return inter / union if union else 0

    merged = list(boxes)
    changed = True
    while changed:
        changed = False
        for i in range(len(merged)):
            for j in range(i + 1, len(merged)):
                if iou(merged[i], merged[j]) > iou_threshold:
                    x1, y1, w1, h1 = merged[i]
                    x2, y2, w2, h2 = merged[j]
                    nx, ny = min(x1, x2), min(y1, y2)
                    nx2, ny2 = max(x1 + w1, x2 + w2), max(y1 + h1, y2 + h2)
                    merged[i] = (nx, ny, nx2 - nx, ny2 - ny)
                    del merged[j]
                    changed = True
                    break
            if changed:
                break
    return merged


# ============================================================
# ÉTAPE 3 — Regroupement en "blocs profilé" (schéma + tableau)
# ============================================================

def detect_profile_blocks(gray: np.ndarray, table_boxes):
    """Pour chaque tableau détecté, retrouve le blob d'encre (dilaté) qui le
    contient -> ce blob représente le schéma associé (imbriqué ou séparé)."""
    h, w = gray.shape
    bin_img = _binarize(gray)

    dilated = cv2.dilate(bin_img, np.ones(BLOCK_DILATE_KERNEL, np.uint8), iterations=1)
    contours, _ = cv2.findContours(dilated, cv2.RETR_EXTERNAL, cv2.CHAIN_APPROX_SIMPLE)
    blobs = [cv2.boundingRect(c) for c in contours if cv2.contourArea(c) > (w * h * 0.005)]

    results = []
    for tb in table_boxes:
        tx, ty, tw, th = tb
        tcx, tcy = tx + tw / 2, ty + th / 2

        matched_blob = None
        for bx, by, bw, bh in blobs:
            if bx <= tcx <= bx + bw and by <= tcy <= by + bh:
                matched_blob = (bx, by, bw, bh)
                break

        if matched_blob is None:
            # Aucun blob englobant trouvé -> repli sur le tableau + marge fixe
            mx = int(w * FALLBACK_MARGIN_RATIO)
            my = int(h * FALLBACK_MARGIN_RATIO)
            block_box = (
                max(0, tx - mx), max(0, ty - my),
                min(w, tx + tw + mx) - max(0, tx - mx),
                min(h, ty + th + my) - max(0, ty - my),
            )
        else:
            block_box = matched_blob

        bx, by, bw, bh = block_box
        # Si le bloc ne dépasse presque pas le tableau lui-même, il n'y a
        # probablement pas de schéma distinct autour.
        area_ratio = (bw * bh) / max(1, (tw * th))
        has_separate_schema = area_ratio > 1.3

        results.append({
            "table_box": tb,
            "block_box": block_box,
            "has_separate_schema": has_separate_schema,
        })

    # Ordre de lecture : haut -> bas, puis gauche -> droite dans chaque bande
    results.sort(key=lambda r: (round(r["table_box"][1] / (h * 0.05)), r["table_box"][0]))
    return results


# ============================================================
# ÉTAPE 4 — OCR structuré du tableau
# ============================================================

@dataclass
class TableRow:
    reference: str = ""
    kg_m: str = ""
    in_: str = ""
    perim: str = ""
    raw_line: str = ""
    a_verifier: bool = False


def _fuzzy_header_match(word: str):
    word_norm = word.strip().lower()
    for canon, variants in EXPECTED_HEADERS.items():
        match = difflib.get_close_matches(word_norm, variants, n=1, cutoff=HEADER_MATCH_CUTOFF)
        if match:
            return canon
    return None


def ocr_table_rows(table_img: np.ndarray, tess_lang: str = "fr+es") -> list[TableRow]:
    """OCR word-level du crop de tableau, reconstruction des colonnes par
    position X relative aux en-têtes détectés."""
    gray = cv2.cvtColor(table_img, cv2.COLOR_BGR2GRAY)
    # Upscale léger : améliore souvent la fiabilité Tesseract sur du scan
    gray = cv2.resize(gray, None, fx=1.5, fy=1.5, interpolation=cv2.INTER_CUBIC)

    data = pytesseract.image_to_data(
        gray, lang=tess_lang, config="--psm 6", output_type=Output.DICT
    )

    n = len(data["text"])
    words = []
    for i in range(n):
        text = data["text"][i].strip()
        if not text:
            continue
        words.append({
            "text": text,
            "x": data["left"][i],
            "y": data["top"][i],
            "w": data["width"][i],
            "h": data["height"][i],
        })

    if not words:
        return []

    # --- Regroupement en lignes par clustering de la coordonnée Y ---
    words.sort(key=lambda w: w["y"])
    line_tolerance = np.median([w["h"] for w in words]) * 0.7
    lines: list[list[dict]] = []
    for w in words:
        placed = False
        for line in lines:
            if abs(line[0]["y"] - w["y"]) <= line_tolerance:
                line.append(w)
                placed = True
                break
        if not placed:
            lines.append([w])
    for line in lines:
        line.sort(key=lambda w: w["x"])

    # --- Repère la ligne d'en-tête et les positions X des colonnes ---
    column_anchors: dict[str, float] = {}
    header_line_idx = None
    for idx, line in enumerate(lines):
        found = {}
        for w in line:
            canon = _fuzzy_header_match(w["text"])
            if canon:
                found[canon] = w["x"] + w["w"] / 2
        if len(found) >= 2:  # au moins 2 en-têtes reconnus -> ligne retenue
            column_anchors = found
            header_line_idx = idx
            break

    rows: list[TableRow] = []

    if header_line_idx is None:
        # Pas d'en-tête détecté : on renvoie tout en texte brut, à vérifier
        for line in lines:
            raw = " ".join(w["text"] for w in line)
            rows.append(TableRow(raw_line=raw, a_verifier=True))
        return rows

    # Complète les colonnes manquantes par une position par défaut répartie
    for canon in EXPECTED_HEADERS:
        column_anchors.setdefault(canon, None)

    def nearest_column(x: float) -> str | None:
        best, best_dist = None, float("inf")
        for canon, anchor_x in column_anchors.items():
            if anchor_x is None:
                continue
            d = abs(anchor_x - x)
            if d < best_dist:
                best, best_dist = canon, d
        return best

    for line in lines[header_line_idx + 1:]:
        raw = " ".join(w["text"] for w in line)
        assigned = {"REFERENCE": [], "KG/M": [], "IN": [], "PERIM": []}
        for w in line:
            col = nearest_column(w["x"] + w["w"] / 2)
            if col:
                assigned[col].append(w["text"])

        row = TableRow(
            reference=" ".join(assigned["REFERENCE"]),
            kg_m=" ".join(assigned["KG/M"]),
            in_=" ".join(assigned["IN"]),
            perim=" ".join(assigned["PERIM"]),
            raw_line=raw,
        )

        # Contrôle qualité simple : la référence doit exister, les colonnes
        # numériques doivent ressembler à des nombres.
        numeric_pattern = re.compile(r"^[\d.,]+$")
        row.a_verifier = (
            not row.reference
            or (row.kg_m and not numeric_pattern.match(row.kg_m.replace(" ", "")))
            or (row.perim and not numeric_pattern.match(row.perim.replace(" ", "")))
        )
        rows.append(row)

    return rows


# ============================================================
# ORCHESTRATION
# ============================================================

def ensure_dirs(base: str):
    for sub in ["images_pages", "blocs", "tableaux", "texte_brut", "debug"]:
        os.makedirs(os.path.join(base, sub), exist_ok=True)


def process_pdf(pdf_path: str, out_dir: str, dpi: int, tess_lang: str, debug: bool,
                 csv_writer, review_writer):
    pdf_name = os.path.splitext(os.path.basename(pdf_path))[0]
    print(f"\n=== {pdf_name} ===")

    for page_num, img in pdf_to_cv_images(pdf_path, dpi=dpi):
        print(f"  page {page_num}...")
        gray = cv2.cvtColor(img, cv2.COLOR_BGR2GRAY)

        # Texte brut de la page entière (traçabilité / filet de sécurité)
        raw_text = pytesseract.image_to_string(gray, lang=tess_lang)
        raw_txt_path = os.path.join(out_dir, "texte_brut", f"{pdf_name}_p{page_num:03d}_brut.txt")
        with open(raw_txt_path, "w", encoding="utf-8") as f:
            f.write(raw_text)

        table_boxes = detect_table_boxes(gray)
        if not table_boxes:
            print("    aucun tableau détecté.")
            continue

        blocks = detect_profile_blocks(gray, table_boxes)

        if debug:
            dbg = img.copy()
            for r in blocks:
                bx, by, bw, bh = r["block_box"]
                tx, ty, tw, th = r["table_box"]
                cv2.rectangle(dbg, (bx, by), (bx + bw, by + bh), (0, 200, 0), 3)
                cv2.rectangle(dbg, (tx, ty), (tx + tw, ty + th), (0, 0, 255), 2)
            cv2.imwrite(os.path.join(out_dir, "debug", f"{pdf_name}_p{page_num:03d}_debug.png"), dbg)

        for profil_idx, r in enumerate(blocks, start=1):
            tx, ty, tw, th = r["table_box"]
            bx, by, bw, bh = r["block_box"]

            table_crop = img[ty:ty + th, tx:tx + tw]
            block_crop = img[by:by + bh, bx:bx + bw]

            table_path = os.path.join(out_dir, "tableaux", f"{pdf_name}_p{page_num:03d}_profil{profil_idx:02d}_tableau.png")
            bloc_path = os.path.join(out_dir, "blocs", f"{pdf_name}_p{page_num:03d}_profil{profil_idx:02d}_bloc.png")
            cv2.imwrite(table_path, table_crop)
            cv2.imwrite(bloc_path, block_crop)

            rows = ocr_table_rows(table_crop, tess_lang=tess_lang)

            for row in rows:
                record = {
                    "pdf_source": pdf_name,
                    "page": page_num,
                    "profil_index": profil_idx,
                    "reference": row.reference,
                    "kg_m": row.kg_m,
                    "in": row.in_,
                    "perim": row.perim,
                    "texte_ligne_brut": row.raw_line,
                    "schema_separe": r["has_separate_schema"],
                    "image_bloc": bloc_path,
                    "image_tableau": table_path,
                    "texte_page_brut": raw_txt_path,
                    "a_verifier": row.a_verifier,
                }
                csv_writer.writerow(record)
                if row.a_verifier:
                    review_writer.writerow(record)


def main():
    parser = argparse.ArgumentParser(description="Extraction catalogue PDF scanné -> images + CSV")
    parser.add_argument("--input", required=True, help="Fichier PDF ou dossier contenant des PDF")
    parser.add_argument("--out", required=True, help="Dossier de sortie")
    parser.add_argument("--dpi", type=int, default=300, help="Résolution de rendu des pages (300 recommandé)")
    parser.add_argument("--lang", default="eng", help="Langue Tesseract (ex: eng, fra, fra+eng)")
    parser.add_argument("--debug", action="store_true", help="Sauvegarde les masques/boîtes de détection pour calibration")
    args = parser.parse_args()

    ensure_dirs(args.out)

    if os.path.isdir(args.input):
        pdf_files = sorted(glob.glob(os.path.join(args.input, "*.pdf")))
    else:
        pdf_files = [args.input]

    if not pdf_files:
        print("Aucun PDF trouvé.")
        return

    fieldnames = [
        "pdf_source", "page", "profil_index", "reference", "kg_m", "in", "perim",
        "texte_ligne_brut", "schema_separe", "image_bloc", "image_tableau",
        "texte_page_brut", "a_verifier",
    ]

    csv_path = os.path.join(args.out, "catalogue.csv")
    review_path = os.path.join(args.out, "a_verifier.csv")

    with open(csv_path, "w", newline="", encoding="utf-8") as f_csv, \
         open(review_path, "w", newline="", encoding="utf-8") as f_review:

        csv_writer = csv.DictWriter(f_csv, fieldnames=fieldnames)
        csv_writer.writeheader()
        review_writer = csv.DictWriter(f_review, fieldnames=fieldnames)
        review_writer.writeheader()

        for pdf_path in pdf_files:
            process_pdf(pdf_path, args.out, args.dpi, args.lang, args.debug, csv_writer, review_writer)

    print(f"\nTerminé. CSV : {csv_path}")
    print(f"Lignes à vérifier manuellement : {review_path}")


if __name__ == "__main__":
    main()
