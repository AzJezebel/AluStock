Acteurs du Système
Acteur	Description
Visiteur	Utilisateur non authentifié, consulte le site vitrine et le catalogue public
Architecte / Ingénieur	Professionnel technique qui utilise le catalogue pour des recherches approfondies
Administrateur (Backoffice)	Gestionnaire du CMS, authentifié, en charge de la mise à jour du contenu


Cas d'Utilisation Détaillés par Acteur
1.  Acteur : Visiteur (Site Vitrine + Catalogue public)
Objectif principal : Découvrir l'offre et les possibilités techniques de l'entreprise.

ID	Cas d'Usage	Description	Points clés
V1	Consulter les réalisations	Visualiser les objets finis en 3D ou en image	Affichage "héro" avec rendus immersifs
V2	Visualiser les possibilités techniques	Voir les gros plans, coupes et détails des assemblages	Images haute définition, zoom possible
V3	Parcourir par gamme	Naviguer les objets par famille de produits (ex: Menuiserie, Structure)	Filtrage par game (famille_objet)
V4	Parcourir par type d'ouvrage	Explorer les objets par catégorie fonctionnelle (ex: Fenêtre, Porte, Véranda)	Filtrage par type_ouvrage
V5	Demander un devis	Envoyer un formulaire de contact depuis la vitrine	Envoi d'un email à l'administrateur
V6	Accéder au catalogue technique	Bascule vers l'espace catalogue (sous-domaine ou page dédiée)	Lien clair "Voir le catalogue technique"
V7	Consulter une fiche modèle	Voir la fiche détaillée d'un objet fini (description, image, caractéristiques)	Informations synthétiques pour le grand public
V8	Consulter une fiche technique	Consulter la fiche technique d'un objet (PDF, plans, cotes, matériaux)	Version accessible depuis le site vitrine également
V9	Télécharger une documentation	Obtenir un PDF / plan technique d'un objet ou d'un profilé	Téléchargement direct sans authentification


2.  Acteur : Ingénieur / Architecte (Catalogue technique avancé)
Objectif principal : Trouver précisément les profilés et compositions pour un projet.

ID	Cas d'Usage	Description	Points clés
I1	Rechercher une pièce / référence	Effectuer une recherche textuelle sur reference ou nom d'un profilé	Moteur de recherche interne (autocomplétion recommandée)
I2	Filtrer les résultats	Appliquer des filtres combinés : game, type_ouvrage, finition, matière, épaisseur	Multi-critères dynamiques
I3	Consulter une fiche technique avancée	Accéder à toutes les cotes, inerties, poids linéaires, tolérances	Vue technique complète avec données numériques
I4	Voir la composition d'un objet fini	Visualiser la liste des profilés nécessaires et leurs quantités	Tableau composition_objet avec quantités et longueurs de coupe
I5	Télécharger un document de contrôle	Exporter une fiche de contrôle qualité ou un plan de fabrication	Formulaire d'envoi ou téléchargement direct
I6	Envoyer un document de contrôle	Envoyer une demande de documentation spécifique (depuis le catalogue)	Formulaire ciblé avec référence produit pré-remplie
I7	Consulter les finitions disponibles	Voir les traitements de surface applicables aux profilés (anodisation, peinture, etc.)	Filtrage par finition


3.  Acteur : Administrateur (Backoffice / CMS)
Objectif principal : Maintenir le catalogue à jour et gérer les interactions.

ID	Cas d'Usage	Description	Points clés
A1	S'authentifier	Se connecter au backoffice via login/password	Sécurisé, session administrateur
A2	Gérer les gammes	CRUD des game (familles d'objets finis)	Ajout, modification, suppression
A3	Gérer les types d'ouvrage	CRUD des type_ouvrage (catégories fonctionnelles)	Permet de classer les objets par usage
A4	Gérer les modèles	CRUD des objet_fini (modèles d'ouvrages)	Associer à une gamme et un type d'ouvrage
A5	Gérer les modèles et leur composition	Associer des profilés à un modèle et définir les quantités	Table de liaison composition_objet
A6	Gérer les pièces	CRUD des profile (profilés aluminium)	Références, dimensions, matières, stock
A7	Gérer les finitions	CRUD des finition (traitements de surface)	Permet d'enrichir les filtres pour les ingénieurs
A8	Uploader médias et documents	Importer images, PDF, plans 3D pour les objets et profilés	Gestion de fichiers, stockage sécurisé
//A9	Consulter les demandes de contact	Voir les demandes de devis et questions des visiteurs	Interface de gestion des leads//

Visiteur (non authentifié)
│
├── Consulter réalisations ──────┬── Dépend de → Gérer modèles (A4)
├── Visualiser possibilités tech─┘
├── Parcourir par gamme ─────────┬── Dépend de → Gérer gammes (A2)
├── Parcourir par type ouvrage ──┘   Dépend de → Gérer types ouvrage (A3)
├── Consulter fiche modèle ──────┬── Dépend de → Gérer modèles (A4)
├── Consulter fiche technique ───┤   Dépend de → Gérer pièces (A6)
├── Télécharger documentation ───┘   Dépend de → Uploader médias (A8)
│
├── //Demander devis// ───────────────┬─ Dépend de → Consulter demandes (A9)
└── Accéder au catalogue technique┘   (redirection)

Ingénieur / Architecte (public)
│
├── Rechercher pièce ────────────┬── Dépend de → Gérer pièces (A6)
├── Filtrer résultats ───────────┤   Dépend de → Gérer finitions (A7)
│                                └── Dépend de → Gérer gammes (A2)
├── Consulter fiche technique ───┬── Dépend de → Gérer pièces (A6)
├── Voir composition objet ──────┤   Dépend de → Gérer modèles (A4)
│                                └── Dépend de → Gérer composition (A5)
├── Télécharger doc contrôle ────┬── Dépend de → Uploader médias (A8)
└── Envoyer doc contrôle ────────┘   (formulaire dynamique)

Administrateur (authentifié)
│
├── Login Admin ───────────────── (sécurisé)
├── Gérer gammes (CRUD)
├── Gérer types ouvrage (CRUD)
├── Gérer modèles (CRUD)
├── Gérer composition (CRUD)
├── Gérer pièces (CRUD)
├── Gérer finitions (CRUD)
├── Uploader médias & documents
└── Consulter demandes de contact