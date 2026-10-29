# Plan d'Action — Phase 3 : Bonnes Pratiques de Conception Ergonomique (Data UX)

## 🎯 Objectif de la Phase
Formaliser l'ensemble des règles ergonomiques et principes de conception visuelle appliqués aux tableaux de bord de Solicode LMS. L'objectif est d'assurer une expérience utilisateur fluide, de minimiser la charge cognitive extrinsèque et de transformer chaque indicateur en un outil d'action pédagogique immédiat.

---

## 🧠 1. Principes Ergonomiques Directeurs

### 1.1. Règle de la Pyramide Inversée (Shneiderman Mantra)
Tout écran de tableau de bord s'organise selon trois niveaux d'information verticaux :
1. **Niveau 1 — Synthèse Instantanée (Haut de page)** :
   - 3 à 5 cartes KPI synthétiques (état de santé global visible en moins de 3 secondes).
   - Indication claire des tendances (progression, régression ou alerte).
2. **Niveau 2 — Tendances & Répartitions Analytiques (Milieu de page)** :
   - Graphiques de distribution (avancement des sprints, temps moyen en file FIFO, répartition des compétences).
   - Visualisation des dynamiques de cohorte.
3. **Niveau 3 — Détail & Action Opérationnelle (Bas de page)** :
   - Tableaux nominatifs interactifs, file d'attente FIFO actionnable.
   - Boutons de remédiation, forages détaillés (drill-down par apprenant).

### 1.2. Principe d'Actionnabilité (Actionable Analytics)
- **Aucune métrique passive** : Un indicateur rouge ou orange doit toujours être assorti d'une action immédiate proposée à l'utilisateur.
  - *Exemple Formateur* : Si l'indicateur $T_{\text{intervention}}$ signale 4 apprenants bloqués au seuil $+10$ min, le bouton *"Ouvrir atelier de remédiation"* est accessible directement sur la carte.
  - *Exemple Apprenant* : Si le délai d'amorçage $T_{\text{amorçage}}$ dépasse le seuil, un lien direct vers la vidéo de démarrage du tutoriel est proposé.

### 1.3. Ratio Données / Encre (Data-to-Ink Ratio — Edward Tufte)
- Bannir les éléments purement décoratifs (ombres portées agressives, biseaux 3D, bordures épaisses).
- Privilégier les espacements aérés (système de grilles Tailwind `gap-6`, `p-6`).
- Utiliser la couleur uniquement pour véhiculer du sens (statuts, alertes), en maintenant des arrière-plans neutres (`bg-slate-50`, `bg-white`).
- Respecter les normes d'accessibilité WCAG AA pour le contraste texte/fond.

---

## 👥 2. Ergonomie Spécifique par Persona

| Persona | Attente Ergonomique Principale | Piège à Éviter | Réponse UX |
| :--- | :--- | :--- | :--- |
| **Apprenant** | Clarté de son cap, auto-régulation, réduction de l'anxiété. | Tableau surchargé de métriques institutionnelles complexes. | Focus sur sa progression personnelle, son rang d'attente et ses tâches immédiates. |
| **Formateur** | Cockpit temps réel, pilotage de la classe sans latence administrative. | Navigation complexe avec multiples clics pour passer un élève en Live Coding. | Console FIFO à portée de clic, bouton unique pour appeler l'élève suivant, alertes $+10$ min bien visibles. |
| **Responsable** | Vision macro, détection des anomalies docimologiques, reporting officiel. | Détail nominatif écrasant le besoin d'analyse stratégique. | Filtres d'agrégation multi-groupes, benchmark inter-classes, export 1-clic des bordereaux officiels. |

---

## ⚡ 3. Directives d'Interactivité & Performance Frontend

1. **Micro-interactions légères (Alpine.js)** :
   - Filtrage instantané côté client sans rechargement de page (`x-model`, filtres réactifs).
   - Dépliage accordéon / tiroir (drawer) pour consulter le détail d'un apprenant sans quitter la vue d'ensemble.
2. **Gestion du rafraîchissement des files temps réel** :
   - Polling discret ou rafraîchissement ciblé de la file FIFO sans scintillement de l'écran.
   - Compteurs d'attente incrémentés en JavaScript local pour fluidifier l'affichage.

---

## ✅ 4. Livrables & Grille d'Évaluation de la Phase 3
- [x] Document de référence des bonnes pratiques finalisé.
- [x] Grille de revue ergonomique formalisée pour évaluer les maquettes de la Phase 4.
- [x] Validation de l'alignement didactique avec les hypothèses de recherche (H1, H2, H3).
