# Plan de Conception — Tableaux de Bord Solicode LMS (PkgTableauBord)

## 📌 Contexte & Objectifs

Le module **PkgTableauBord** a pour mission de fournir des interfaces de pilotage pédagogique et d'aide à la décision adaptées à trois profils clés :
1. **L'Apprenant** : Autonomie, auto-régulation cognitive et visibilité sur son parcours.
2. **Le Formateur** : Régulation collective, pilotage de la file d'attente (Live Coding), détection précoce des blocages et remédiation ciblée.
3. **Le Responsable de formation** : Gouvernance pédagogique, intégrité docimologique (règle 65/35, intégrité face à l'IA), conformité au référentiel national et pilotage multi-groupes/établissements.

Ce plan intègre les **9 indicateurs opérationnels de recherche** formalisés dans l'[Annexe M](file:///c:/AppServer/solicode-lms/cahiers-charges/PkgTableauBord/Annexe%20M%20-%20Indicateurs%20op%C3%A9rationnels%20de%20recherche.md), les exigences du [Cahier des charges](file:///c:/AppServer/solicode-lms/cahiers-charges/PkgTableauBord/cahier_charge.md), ainsi que les standards de l'**Architecture Frontend V2** (Tailwind CSS, Alpine.js).

---

## 🧭 Phase 1 : Détermination, Cartographie & Niveaux d'Agrégation des Indicateurs

### 1.1. Matrice des Indicateurs de Recherche (Annexe M)

| Axe & Hypothèse | Indicateur | Symbole | Unité | Source de données SoliLMS | Déclencheur / Action requise |
| :--- | :--- | :---: | :---: | :--- | :--- |
| **H1 (Apprenant)** | Temps d'amorçage moyen | $T_{\text{amorçage}}$ | Heures ($h$) | Horodatage activation sprint $\rightarrow$ 1ère tâche soumise (*En validation*) | Si délai élevé : alerte blocage initial / remédiation tuteur |
| **H1 (Apprenant)** | Taux validation gating 1er essai | $R_{\text{gating}}$ | % | Évaluation Live Coding Prototype ($N2$) essai 1 | Si $< 70\%$ : session de renforcement / trinôme de soutien |
| **H1 (Apprenant)** | Boucles de révision par tâche | $F_{\text{révision}}$ | Itérations | Nb de transitions vers le statut *Révision nécessaire* | Si $> 2$ : consignes ambiguës ou clarification requise |
| **H2 (Formateur)** | Charge logistique hebdomadaire | $C_{\text{logistique}}$ | $h/\text{semaine}$ | Logs d'activité sur console pilotage & bordereaux | Si élevée : simplifier exports & automatiser notifications |
| **H2 (Formateur)** | Délai traitement file d'attente | $T_{\text{file}}$ | Minutes ($\text{min}$) | Horodatage *En validation* $\rightarrow$ Prise en charge Live Coding | Si $> 45\,\text{min}$ : basculer en évaluation groupée (jusqu'à 4) |
| **H2 (Formateur)** | Taux remédiation ciblée | $T_{\text{intervention}}$ | % | Traces post-Live Coding collectif ($+10\,\text{min}$) | Objectif $> 80\%$ de prise en charge des apprenants bloqués |
| **H3 (Institution)** | Intégrité face à l'IA (Pearson) | $r$ | $[-1, 1]$ | Corrélation $N2$ (Live Coding individuel) vs $N3$ (Projet IA) | Si divergence ($N3 \gg N2$) : alerte surcomplaisance IA |
| **H3 (Institution)** | Couverture Pédagogique (ICP) | $\text{ICP}$ | % | Cumul des micro-compétences validées vs référentiel | Si $< 90\%$ en fin de module : programmer rattrapage |
| **H3 (Institution)** | Conformité conversion 65/35 | $\tau_{\text{conformité}}$ | % | Vérification calcul automatique des notes CC (20) & EFM (40) | Cible $100\%$ (zéro divergence arithmétique) |

### 1.2. Niveaux d'Agrégation & Granularité

Les données doivent être consultables selon trois niveaux d'agrégation hiérarchiques :
1. **Niveau Individuel (Micro)** : Analyse unitaire pour l'apprenant (ses scores, son rythme, son rang d'attente FIFO).
2. **Niveau Groupe / Promotion (Méso)** : Moyennes arithmétiques, écarts-types, taux d'avancement du sprint, dispersion des notes pour le formateur.
3. **Niveau Établissement / Filière (Macro)** : Comparatif inter-groupes, taux de réussite global, couverture pédagogique globale pour la gouvernance.

---

## 🎨 Phase 2 : Charte Graphique & Design System Frontend V2

Conformément au standard V2 de Solicode LMS ([charte V2](file:///c:/AppServer/solicode-lms/cahiers-charges/PkgPasserQcm/charte-graphique/index.html)), le tableau de bord est conçu avec **Tailwind CSS** et **Alpine.js** (sans Bootstrap ni jQuery).

### 2.1. Guide de Style & Tokens Graphiques

- **Typographie** : Famille `Inter` (`sans-serif`), hiérarchie stricte (`text-xs` à `text-2xl`), font weights de 400 à 700.
- **Palette de Couleurs Fonctionnelle** :
  - *Neutres (Fond & Cartes)* : Fond de page `bg-slate-50`, cartes `bg-white`, bordures `border-slate-200`, textes `text-slate-900` / `text-slate-500`.
  - *Primaire & Identité (Indigo)* : Teinte principale d'accentuation `indigo-600`, badges doux `bg-indigo-50 text-indigo-700`.
  - *Statut Succès / Maîtrise (Emerald)* : Validation, progression dans les temps, conformité optimale (`emerald-600`, `bg-emerald-50`).
  - *Statut Attention / Révision (Amber)* : Délai approchant de la limite, boucle de révision active (`amber-600`, `bg-amber-50`).
  - *Statut Danger / Blocage (Rose / Red)* : Retard critique, décrochage à $+10$ min, divergence d'intégrité IA (`rose-600`, `bg-rose-50`).
  - *Statut Information / En attente (Sky / Slate)* : File d'attente FIFO, statut planifié (`sky-600`, `slate-400`).

### 2.2. Bibliothèque de Composants UI Dashboard

1. **KPI Stat Card** : Valeur clé grand format, libellé, icône sémantique, badge de variation/tendance (+/- vs moyenne) et barre de progression mini.
2. **File d'Attente Interactive (FIFO Component)** : Liste ordonnée en temps réel avec statut d'attente (timer dynamique), bouton d'action directe ("Démarrer Live Coding"), badge individuel ou groupe.
3. **Zone de Remédiation Prioritaire** : Liste dynamique des apprenants non débloqués après les 10 minutes de la séance plénière.
4. **Jauge d'Intégrité & Corrélation IA** : Graphique de dispersion (Scatter plot) ou jauge synthétique comparant $N2$ (Live Coding) et $N3$ (Projet Fil Rouge).
5. **Barre de Filtre Contextuelle (Breadcrumb & Sélecteurs)** : Sélecteur d'établissement $\rightarrow$ Groupe $\rightarrow$ Sprint $\rightarrow$ Apprenant.

---

## 📐 Phase 3 : Bonnes Pratiques de Conception (Data UX & Analytics)

Pour garantir un usage ergonomique et actionnable, la conception applique les principes reconnus en ergonomie cognitive :

1. **Règle de la Pyramide Inversée (Shneiderman Mantra)** :
   - *Haut de page (Synthèse globale)* : 4 KPI Cards majeurs donnant l'état de santé instantané en moins de 3 secondes.
   - *Milieu de page (Tendances & Répartitions)* : Visualisations graphiques (avancement temporel, flux de la file FIFO, répartition des compétences).
   - *Bas de page (Détail & Action)* : Listes détaillées actionnables (file de passage, tableau nominatif avec boutons d'intervention).
2. **Principe d'Actionnabilité (Actionable Insights)** :
   - Aucun chiffre orphelin : chaque métrique critique est accompagnée d'un déclencheur d'action (ex: un bouton *"Créer session de remédiation"* à côté du taux d'intervention).
3. **Ratio Données/Encre (Data-to-Ink Ratio - Edward Tufte)** :
   - Élimination des bordures lourdes et des ombres agressives.
   - Utilisation d'arrière-plans subtils et de micro-typographies pour maximiser la lisibilité sans surcharge cognitive.
4. **Auto-Actualisation & Réactivité Non-Intrusive** :
   - Mise à jour réactive des files d'attente via Alpine.js sans rechargement de page.

---

## 🖥️ Phase 4 : Spécification des Maquettes HTML & CSS

Un dossier dédié `cahiers-charges/PkgTableauBord/maquettes/` sera créé avec les prototypes statiques suivants :

```
cahiers-charges/PkgTableauBord/
├── charte-graphique/
│   └── index.html               # Page de référence du Design System (Tokens, Composants)
└── maquettes/
    ├── dashboard-apprenant.html   # Vue apprenant (progression perso, T_amorçage, statut gating)
    ├── dashboard-formateur.html   # Vue formateur (file FIFO Live Coding, alerte +10min, logistique)
    └── dashboard-responsable.html # Vue gouvernance (ICP, règle 65/35, corrélation IA, multi-groupes)
```

### 4.1. Maquette 1 : Tableau de Bord Apprenant (`dashboard-apprenant.html`)
- **En-tête** : Profil apprenant, sprint actif, statut actuel (ex: *En préparation Live Coding*).
- **Cartes KPI** :
  - Temps d'amorçage individuel ($T_{\text{amorçage}}$ vs moyenne groupe).
  - Taux d'avancement des tâches (Tutoriels Niveau 1, Prototype Niveau 2).
  - Rang d'attente en direct dans la file de Live Coding.
  - Estimation de la note continue (CC) selon les prototypes validés.
- **Corps principal** :
  - Kanban compact ou Timeline de l'UA en cours.
  - Alerte personnalisée avec lien vers tutoriel si retour en *Révision nécessaire*.

### 4.2. Maquette 2 : Tableau de Bord Formateur (`dashboard-formateur.html`)
- **En-tête** : Sélecteur de groupe, sélection du sprint actif, bascule rapide des modes de classe.
- **Cartes KPI** :
  - Longueur et délai moyen de la file d'attente Live Coding ($T_{\text{file}}$).
  - Taux de validation au gating au premier essai ($R_{\text{gating}}$).
  - Taux d'intervention et remédiation active ($T_{\text{intervention}}$).
  - Charge logistique estimée sur la semaine ($C_{\text{logistique}}$).
- **Console d'Action Opérationnelle** :
  - *Composant File FIFO* : Apprenants en attente de passage Live Coding (boutons *Valider*, *Ajourner / Remédiation*, *Passage groupé 4x4*).
  - *Zone d'Alerte "Seuil 10 min"* : Liste des apprenants présents n'ayant pas soumis leur tutoriel 10 min après le Live Coding collectif.

### 4.3. Maquette 3 : Tableau de Bord Responsable de Formation (`dashboard-responsable.html`)
- **En-tête** : Sélecteur d'établissement, filtres multi-groupes, sélection de la période académique.
- **Cartes KPI** :
  - Taux de Couverture Pédagogique national ($\text{ICP}$).
  - Taux de conformité docimologique 65/35 ($\tau_{\text{conformité}}$).
  - Indice de corrélation Authenticité / IA ($r$ de Pearson).
  - Taux de réussite global aux prototypes ($N2$) et projets ($N3$).
- **Visualisations Analytiques** :
  - Matrice comparative inter-groupes (taux de validation, temps de passage, alertes décrochage).
  - Scatter plot d'intégrité IA ($N2$ vs $N3$) permettant d'isoler immédiatement les anomalies.
  - Module d'export officiel des bordereaux CC et EFM sans ressaisie manuelle.

---

## 🚀 Phase 5 : Planification en Sprints de Réalisation

Conformément à la règle de conception logicielle (toute évolution de base de données est concentrée en Sprint 1) :

```mermaid
gantt
    title Déploiement du Module PkgTableauBord
    dateFormat  YYYY-MM-DD
    section Sprint 1 : Conception & BDD
    Définition BDD & Entités / Vues SQL         :done, s1_1, 2026-10-13, 2d
    Migration & Gapp meta:sync                 :done, s1_2, after s1_1, 1d
    Charte Graphique HTML/CSS V2               :active, s1_3, after s1_2, 2d
    section Sprint 2 : Maquettes UI
    Maquette HTML/CSS Apprenant                :s2_1, after s1_3, 2d
    Maquette HTML/CSS Formateur (FIFO & 10min)  :s2_2, after s2_1, 2d
    Maquette HTML/CSS Responsable (Gouvernance):s2_3, after s2_2, 2d
    section Sprint 3 : Services de Calcul
    LearningAnalyticsService (Annexe M)        :s3_1, after s2_3, 3d
    Algorithme de conversion docimologique 65/35:s3_2, after s3_1, 2d
    section Sprint 4 : Intégration V2 & Alpine
    Composants Blade V2 & Layout dédié         :s4_1, after s3_2, 3d
    Contrôleurs et injection ViewState         :s4_2, after s4_1, 2d
    Interactions dynamiques Alpine.js          :s4_3, after s4_2, 2d
```

### Détail des Sprints :
- **Sprint 1 — Cadrage, Base de Données & Charte Graphique V2** :
  - Modélisation des tables ou vues d'agrégation d'indicateurs si persistance requise.
  - Exécution de la migration par le développeur, puis `gapp meta:sync`.
  - Création de la page `charte-graphique/index.html` (tokens, styles, composants).
- **Sprint 2 — Prototypage HTML / CSS des Maquettes** :
  - Réalisation des 3 fichiers de maquettes statiques dans `maquettes/`.
  - Validation visuelle avec le développeur et les formateurs.
- **Sprint 3 — Couche Services & Métier Backend** :
  - Création du `TableauBordService` et de ses traits (`DashboardCalculTrait`, `DashboardGetterTrait`).
  - Implémentation des formules mathématiques exactes de l'Annexe M (Pearson, ICP, conformité, files d'attente).
- **Sprint 4 — Intégration Frontend Blade & Alpine.js** :
  - Découpage en composants réutilisables (`<x-tableau-bord.kpi-card>`, `<x-tableau-bord.fifo-table>`, etc.).
  - Connexion des données réelles issues du contrôleur aux composants.
  - Dynamisation frontend (actualisation temps réel, interaction file FIFO).
