GAMME (id, nom, slug, description, image_cover, ordre_affichage, timestamps)
  1,n ──┬── 1,1 MODELE (id, reference, nom, slug, gamme_id, type_ouvrage_id, description_courte, 
         │                    description_technique, largeur_min_mm, largeur_max_mm, 
         │                    hauteur_min_mm, hauteur_max_mm, performance_thermique, 
         │                    performance_acoustique, image_principale, est_actif, timestamps)
         │
         └── 0,n PIECE (id, reference, designation, slug, type_piece_id, gamme_id, matiere,
                         longueur_barre_mm, poids_lineaire_kg_m, section_largeur_mm,
                         section_hauteur_mm, epaisseur_paroi_mm, moment_inertie_x_cm4,
                         moment_inertie_y_cm4, module_elasticite_x_cm3, module_elasticite_y_cm3,
                         image_coupe, est_disponible, timestamps)

TYPE_OUVRAGE (id, nom, slug, description, icone, timestamps)
  1,n ──┬── 1,1 MODELE (voir ci-dessus)

TYPE_PIECE (id, nom, slug, description, timestamps)
  1,n ──┬── 1,1 PIECE (voir ci-dessus)

MODELE (1,n) ── COMPOSITION_MODELE (modele_id, piece_id, quantite, unite, ordre, 
                                    longueur_coupe_mm, commentaire, timestamps) ── (1,n) PIECE

FINITION (id, nom, slug, code_ral, type_finition, description, timestamps)
  0,n ── PIECE_FINITION (piece_id, finition_id, est_par_defaut, timestamps) ── 0,n PIECE

PIECE (1,n) ── CARACTERISTIQUE (id, piece_id, cle, valeur, unite, ordre_affichage, timestamps)

MEDIA (id, chemin_fichier, titre, description, type_media, est_principal, timestamps)
  ── MEDIA_MORPH (media_id, mediable_id, mediable_type, ordre, timestamps) 
  ── (0,n) GAMME / TYPE_OUVRAGE / MODELE / PIECE (via polymorphique)

DOCUMENT (id, chemin_fichier, titre, description, type_document, taille_octets, timestamps)
  ── DOCUMENT_ASSOCIATION (document_id, modele_id, piece_id, timestamps)
  ── (0,n) MODELE / PIECE