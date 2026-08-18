┌─────────────────────────────────────────────────────────────────────────────────────┐
│                    DIAGRAMME D'ÉTAT - CYCLE DE VIE D'UN MODÈLE                      │
└─────────────────────────────────────────────────────────────────────────────────────┘

                         ┌─────────────────────────────────────────┐
                         │                                         │
                         │   ┌─────────────────────────────────┐   │
                         │   │        DRAFT                    │   │
                         │   │  (est_actif = false)            │   │
                         │   │  (Créé par l'admin)             │   │
                         │   └───────────────┬─────────────────┘   │
                         │                   │                     │
                         │                   │ Saisie des données  │
                         │                   │ (composition, docs) │
                         │                   ▼                     │
                         │   ┌─────────────────────────────────┐   │
                         │   │      EN RÉVISION                │   │
                         │   │  (Composition vérifiée)         │   │
                         │   │  (Documents attachés)           │   │
                         │   └───────────────┬─────────────────┘   │
                         │                   │                     │
                         │                   │ Validation par      │
                         │                   │ gestionnaire        │
                         │                   ▼                     │
                         │   ┌─────────────────────────────────┐   │
                         │   │         PUBLIÉ                  │   │
                         │   │  (est_actif = true)             │   │
                         │   │  (Visible dans le catalogue)    │   │
                         │   └───────────────┬─────────────────┘   │
                         │                   │                     │
                         │                   │ Mise à jour         │
                         │                   ▼                     │
                         │   ┌─────────────────────────────────┐   │
                         │   │        ARCHIVÉ                  │   │
                         │   │  (est_actif = false)            │   │
                         │   │  (Plus visible, mais conservé)  │   │
                         │   └─────────────────────────────────┘   │
                         │                                         │
                         └─────────────────────────────────────────┘

                              Légende :
                         ───────────────────────
                         → Transition automatique (soumission)
                         ──→ Transition manuelle (action admin)