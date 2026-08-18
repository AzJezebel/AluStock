┌─────────────────────────────────────────────────────────────────────────────────────┐
│                     DIAGRAMME DE SÉQUENCE - FICHE TECHNIQUE MODÈLE                  │
└─────────────────────────────────────────────────────────────────────────────────────┘

Ingénieur          Contrôleur          Modele           Composition      Piece
   │                  │                  │                  │              │
   │  1. GET /modeles/{slug}             │                  │              │
   │─────────────────>│                  │                  │              │
   │                  │                  │                  │              │
   │                  │  2. findBySlug() │                  │              │
   │                  │─────────────────>│                  │              │
   │                  │                  │                  │              │
   │                  │  3. Modele trouvé│                  │              │
   │                  │<─────────────────│                  │              │
   │                  │                  │                  │              │
   │                  │  4. getComposition()                │              │
   │                  │────────────────────────────────────>│              │
   │                  │                  │                  │              │
   │                  │  5. Lignes de composition           │              │
   │                  │<────────────────────────────────────│              │
   │                  │                  │                  │              │
   │                  │                  │                  │ 6. getPiece()│
   │                  │                  │                  │─────────────>│           A savoir si hybride : Socle commun en colonnes fixes + EAV pour les spécificités. (Modele, Piece ont deja bcp de colonnes specifiques a leur caracteristique)
   │                  │                  │                  │              │           Ou EAV pur et tout les spec rentrent dans la table Caracteristique
   │                  │                  │                  │ 7. Pièce     │
   │                  │                  │                  │<─────────────│
   │                  │                  │                  │              │
   │                  │  8. getCaracteristiques()           │              │
   │                  │───────────────────────────────────────────────────>│
   │                  │                  │                  │              │
   │                  │  9. Liste des caractéristiques      │              │
   │                  │<───────────────────────────────────────────────────│
   │                  │                  │                  │              │
   │                  │  10. getDocuments()                 │              │
   │                  │────────────────────────────────────>│              │
   │                  │                  │                  │              │
   │                  │  11. Liste des documents            │              │
   │                  │<────────────────────────────────────│              │
   │                  │                  │                  │              │
   │  12. Vue complète│                  │                  │              │
   │<─────────────────│                  │                  │              │
   │                  │                  │                  │              │
   │  13. Affiche la fiche technique     │                  │              │
   │  (modèle + composition + docs)      │                  │              │
   │                  │                  │                  │              │