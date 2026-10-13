# Plan d'Action — Phase 2 : Charte Graphique & Design System Frontend V2

## 🎯 Objectif de la Phase
Concevoir la charte graphique et le Design System interactif de la version V2 de l'application pour le module **PkgTableauBord**, en conformité stricte avec les standards de l'architecture V2 ([charte existante PkgPasserQcm](file:///c:/AppServer/solicode-lms/cahiers-charges/PkgPasserQcm/charte-graphique/index.html)) : Tailwind CSS, Alpine.js, police `Inter` et exclusion formelle de Bootstrap et jQuery.

---

## 🎨 1. Spécification des Tokens Graphiques

### 1.1. Typographie & Lisibilité
- Police de caractères : `Inter`, sans-serif (chargée via Google Fonts).
- Échelle typographique :
  - `text-xs` (12px) : Badges, timestamps, libellés secondaires.
  - `text-sm` (14px) : Textes de navigation, cellules de tableaux, descriptions.
  - `text-base` (16px) : Textes courants, boutons principaux.
  - `text-lg` / `text-xl` (18px - 20px) : Titres de sections et de cartes.
  - `text-2xl` / `text-3xl` (24px - 30px) : Valeurs majeures des indicateurs KPI.

### 1.2. Palette de Couleurs Fonctionnelle
| Catégorie | Classe Tailwind | Usage |
| :--- | :--- | :--- |
| **Fond d'application** | `bg-slate-50` | Arrière-plan global, repos visuel. |
| **Surfaces & Cartes** | `bg-white border-slate-200` | Cartes KPI, tableaux, panneaux latéraux. |
| **Texte Principal** | `text-slate-900` | Valeurs chiffrées, titres. |
| **Texte Secondaire** | `text-slate-500` | Libellés d'indicateurs, métadonnées. |
| **Accent & Identité** | `bg-indigo-600 text-indigo-700` | Boutons d'action, filtres actifs, KPI primaire. |
| **Succès / Optimal** | `bg-emerald-50 text-emerald-700` | Gating validé, progression dans les temps, ICP $\ge 90\%$. |
| **Alerte / Révision** | `bg-amber-50 text-amber-700` | Temps d'attente élevé, boucle de révision active. |
| **Critique / Blocage** | `bg-rose-50 text-rose-700` | Retard critique, apprenant bloqué $> 10$ min, écart IA important. |
| **File FIFO / Neutre** | `bg-sky-50 text-sky-700` | Statut en attente de passage, file Live Coding. |

---

## 🧩 2. Bibliothèque de Composants UI Dashboard

La charte graphique matérialise une suite de composants réutilisables dédiés aux tableaux de bord :

1. **KPI Stat Card** :
   - Valeur principale (`text-2xl font-bold`).
   - Libellé explicite avec infobulle didactique.
   - Badge d'évolution (+/- comparé à la moyenne ou au sprint précédent).
   - Barre de progression mini ou micro-graphique d'étincelle (sparkline).
2. **File d'Attente Interactive (Composant FIFO Live Coding)** :
   - Rang de passage numéroté.
   - Nom apprenant avec avatar UI.
   - Timer d'attente dynamique (`min:sec`).
   - Bouton d'action directe contextuel (*"Démarrer Live Coding"* / *"Passage groupé"*).
3. **Bannière d'Alerte "Seuil 10 min Post-Live Coding"** :
   - Zone haute visibilité pour les apprenants nécessitant une remédiation immédiate.
   - Bouton de création directe d'une fiche d'intervention.
4. **Jauge d'Avancement & Taux d'Intégrité IA** :
   - Jauge circulaire ou barre segmentée de progression.
   - Indicateur de corrélation $r$ avec code couleur de conformité.
5. **Barre de Filtrage & Drill-Down** :
   - Navigation hiérarchique : Établissement $\rightarrow$ Groupe/Classe $\rightarrow$ Sprint $\rightarrow$ Apprenant.

---

## 💻 3. Livrable Technique : `charte-graphique/index.html`

- Création du dossier `cahiers-charges/PkgTableauBord/charte-graphique/`.
- Création du fichier interactif complet `index.html` intégrant :
  - Tailwind CSS via CDN.
  - Alpine.js pour simuler l'interactivité des composants (tabs, boutons d'état, filtres).
  - Présentation vivante de tous les tokens, boutons, cartes KPI et tableaux.

---

## ✅ 4. Checklist de Réalisation & Validation
- [ ] Dossier `charte-graphique/` initialisé.
- [ ] Fichier `charte-graphique/index.html` conçu et testé dans le navigateur.
- [ ] Cohérence chromatique validée avec `PkgPasserQcm`.
- [ ] Validation responsive (Mobile, Tablette, Desktop).
