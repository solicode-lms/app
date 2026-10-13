# Plan d'Action — Phase 2 : Charte Graphique & Design System Frontend V2

## 🎯 Objectif de la Phase
Concevoir la charte graphique et le Design System interactif de la version V2 de l'application pour le module **PkgTableauBord**, en conformité stricte avec les standards de l'architecture V2 ([charte existante PkgPasserQcm](file:///c:/AppServer/solicode-lms/cahiers-charges/PkgPasserQcm/charte-graphique/index.html)) : Tailwind CSS, Alpine.js, police `Inter` et exclusion formelle de Bootstrap et jQuery.

Ce livrable constitue le socle visuel et ergonomique obligatoire avant le développement des maquettes (Phase 4) et des composants Blade V2 (Sprint 4).

---

## 🎨 1. Spécification des Tokens Graphiques

### 1.1. Typographie & Hiérarchie
- **Famille de police** : `Inter`, sans-serif (chargée via Google Fonts).
- **Échelle typographique et rôles** :
  - `text-xs` (12px) : Badges, timestamps, libellés secondaires d'indicateurs.
  - `text-sm` (14px) : Textes de navigation, cellules de tableaux, descriptions didactiques.
  - `text-base` (16px) : Textes courants, boutons principaux d'action.
  - `text-lg` / `text-xl` (18px - 20px) : Titres de sections, sous-totaux et en-têtes de cartes.
  - `text-2xl` / `text-3xl` (24px - 30px) : Valeurs majeures des indicateurs KPI et métriques clés.

### 1.2. Palette de Couleurs UI/UX Standard & Équilibrée
La charte repose sur un équilibre visuel complet et professionnel (identité Indigo Solicode LMS, contrastes nets, boutons d'action valorisés et statuts sémantiques clairs) :

| Catégorie | Teinte & Classes Tailwind (Fond / Texte / Bordure) | Usage Métier & Principe Ergonomique |
| :--- | :--- | :--- |
| **Fond d'application** | `bg-slate-50` | Arrière-plan standard propre et reposant. |
| **Surfaces & Cartes** | `bg-white border-slate-200 shadow-sm` | Cartes KPI, consoles et conteneurs délimités. |
| **Texte Principal** | `text-slate-900 font-bold` | Valeurs chiffrées majeures, titres de sections. |
| **Texte Secondaire** | `text-slate-500` | Unités, labels d'indicateurs, métadonnées. |
| **Action Principale** | `bg-indigo-600 hover:bg-indigo-700 text-white shadow-sm` | Boutons d'action prioritaires (Identité SoliLMS). |
| **Succès / Optimal** | `bg-emerald-50 text-emerald-700 border-emerald-200` | Gating validé au 1er essai, progression dans les temps, $\text{ICP} \ge 90\%$. |
| **Avertissement / Révision** | `bg-amber-50 text-amber-700 border-amber-200` | Tâches en révision, délai d'attente élevé en file FIFO. |
| **Alerte Critique** | `bg-rose-50 text-rose-700 border-rose-200` | Apprenant bloqué $> 10$ min, divergence docimologique IA $> 5$ pts. |
| **File FIFO / Neutre** | `bg-sky-50 text-sky-700 border-sky-200` | Apprenant en attente dans la file Live Coding. |

---

## 🧩 2. Bibliothèque de Composants UI Dashboard

La charte graphique matérialise une suite complète de composants réutilisables dédiés aux tableaux de bord des trois acteurs (Apprenant, Formateur, Responsable) :

### 2.1. Composants Atomiques & Moléculaires
1. **Badges d'État & Pastilles** :
   - Statut de gating : *Validé 1er essai* (`emerald`), *Révision nécessaire* (`amber`), *Non présenté* (`slate`).
   - File d'attente : *Rang N* (`sky`), *En évaluation* (`indigo`), *Ajourné* (`rose`).
2. **Boutons d'Action Contextuels** :
   - Bouton Primaire (`bg-indigo-600 hover:bg-indigo-700 text-white`).
   - Bouton Rapide Formateur (*"Démarrer Live Coding"*, *"Session 4x4"*, *"Valider"*).
   - Bouton d'Alerte & Entraide (*"Former trinôme"*, *"Déclencher remédiation"*).
3. **Micro-Visualisations** :
   - Barres de progression segmentées (taux d'avancement sprint).
   - Sparklines SVG (tendance d'activité et charge logistique).

### 2.2. Composants Organismes (Cockpits & Consoles)
1. **KPI Stat Card Réactive** :
   - Valeur principale en grand format (`text-2xl font-bold`).
   - Libellé explicite avec infobulle didactique au survol.
   - Badge de variation relative (+/- comparé à la cohorte ou au sprint précédent).
   - Mini-jauge circulaire ou barre d'objectif intégrée.
2. **File d'Attente Interactive (Console FIFO Live Coding)** :
   - Rang de passage prioritaire numéroté.
   - Identité apprenant avec avatar UI et statut de présence.
   - Chronomètre d'attente dynamique en temps réel (`min:sec`).
   - Menu d'actions rapides (1-à-1, groupé, ajournement).
3. **Bannière d'Alerte "Seuil 10 min Post-Live Coding"** :
   - Conteneur d'alerte haute visibilité avec pulsation visuelle subtile.
   - Liste des apprenants en situation de blocage.
   - Action en un clic pour affecter un tuteur ou créer une fiche d'intervention.
4. **Jauge d'Avancement & Corrélation IA (Scatter Plot)** :
   - Représentation graphique de la dispersion des notes ($N2$ vs $N3$).
   - Mise en évidence des divergences $\Delta_{\text{IA}} > +5$ points pour le jury.
5. **Barre de Filtrage & Drill-Down Hiérarchique** :
   - Navigation contextuelle : Établissement $\rightarrow$ Filière $\rightarrow$ Groupe/Classe $\rightarrow$ Sprint $\rightarrow$ Apprenant.

---

## 💻 3. Livrable Technique : `charte-graphique/index.html`

- **Emplacement cible** : `cahiers-charges/PkgTableauBord/charte-graphique/index.html`.
- **Technologies intégrées** :
  - **Tailwind CSS (CDN)** : Utilisation exclusive des classes utilitaires pour l'ensemble du style.
  - **Alpine.js (CDN)** : Orchestration de l'interactivité (onglets de navigation du styleguide, simulateur de file FIFO, compteurs temps réel, infobulles).
  - **Google Fonts `Inter`** : Typographie officielle SoliLMS V2.
  - **Heroicons (SVG inline)** : Icônes vectorielles légères et sémantiques.

---

## 🗺️ 4. Déroulement de la Réalisation

```mermaid
flowchart TD
    A["Étape 1 : Initialisation structurelle<br>(Dossier charte-graphique & squelette HTML5)"] --> B["Étape 2 : Intégration des Tokens & Styles de base<br>(Nuancier, Typographie, Boutons, Badges)"]
    B --> C["Étape 3 : Développement des Organismes Métier<br>(KPI Cards, Console FIFO, Bannière 10min, Scatter Plot)"]
    C --> D["Étape 4 : Dynamisation interactive Alpine.js<br>(Minuterie FIFO, filtre de cohorte, bascule d'état)"]
    D --> E["Étape 5 : Revue Responsive & Validation Visuelle<br>(Mobile, Tablette, Desktop)"]
```

---

## ✅ 5. Checklist de Réalisation & Validation
- [x] Dossier `cahiers-charges/PkgTableauBord/charte-graphique/` initialisé.
- [x] Fichier `charte-graphique/index.html` conçu et documenté.
- [x] Cohérence chromatique et typographique validée avec `PkgPasserQcm`.
- [x] Interactions Alpine.js fonctionnelles sans rechargement de page.
- [x] Validation responsive complète (Mobile, Tablette, Desktop).
