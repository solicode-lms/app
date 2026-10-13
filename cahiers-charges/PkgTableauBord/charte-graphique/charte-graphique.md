# Charte Graphique & Design System V2 — PkgTableauBord

## 🎯 1. Principes Directeurs & Ergonomie Visuelle

Cette charte graphique définit les standards visuels, comportementaux et techniques de l'interface Frontend V2 pour le module **PkgTableauBord** de Solicode LMS.

### Piliers de Conception UI/UX :
1. **Clarté Opérationnelle & Densité Équilibrée** : Une interface standard, vivante et généreuse (non minimaliste), offrant une lisibilité immédiate des métriques de pilotage pour les trois acteurs (Apprenant, Formateur, Responsable).
2. **Identité Visuelle SoliLMS** : Respect strict de l'univers visuel de la plateforme et cohérence directe avec la charte existante de [PkgPasserQcm](file:///c:/AppServer/solicode-lms/cahiers-charges/PkgPasserQcm/charte-graphique/index.html) (teintes d'accent Indigo, gris neutres Slate, polices Inter).
3. **Ergonomie Docimologique** : Mise en valeur immédiate des seuils d'alerte didactiques (retard seuil 10 min post-Live Coding, divergence d'intégrité IA $r$, file d'attente FIFO).
4. **Stack Frontend V2 Stricte** : Développée exclusivement en **Tailwind CSS** et **Alpine.js**, sans aucune dépendance à Bootstrap ou jQuery.

---

## 🎨 2. Design Tokens

### 2.1. Typographie
- **Police officielle** : `Inter`, sans-serif (chargée via Google Fonts).
- **Échelle typographique** :

| Classe Tailwind | Taille | Poids standard | Cas d'application dans le Dashboard |
| :--- | :--- | :--- | :--- |
| `text-xs` | 12px | `font-semibold` / `font-bold` | Badges d'état, tags de statut, timestamps, compteurs en file. |
| `text-sm` | 14px | `font-medium` / `font-semibold` | Lignes de tableaux, navigation, libellés explicatifs, infobulles. |
| `text-base` | 16px | `font-bold` | Intitulés de boutons principaux, libellés de cartes d'action. |
| `text-lg` / `text-xl` | 18px - 20px | `font-bold` / `font-extrabold` | Titres de sections, en-têtes de cartes et de consoles. |
| `text-3xl` | 30px | `font-extrabold` | Valeurs majeures des 9 indicateurs KPI ($T_{\text{amorçage}}$, $R_{\text{gating}}$, etc.). |

---

### 2.2. Nuancier & Palette Sémantique Standard

| Rôle Sémantique | Teinte Tailwind | Code HEX | Classes Appliquées | Usage Métier |
| :--- | :--- | :--- | :--- | :--- |
| **Fond d'Application** | `slate-50` | `#f8fafc` | `bg-slate-50` | Arrière-plan global, repos visuel et découpage net des cartes. |
| **Surfaces & Cartes** | `white` / `slate-200` | `#ffffff` / `#e2e8f0` | `bg-white border-slate-200 shadow-sm` | Conteneurs KPI, consoles de commande et tableaux de données. |
| **Texte Principal** | `slate-900` | `#0f172a` | `text-slate-900 font-bold` | Chiffres clés des indicateurs, titres majeurs, noms d'apprenants. |
| **Texte Secondaire** | `slate-500` | `#64748b` | `text-slate-500` | Libellés descriptifs, unités de mesure, horodatages d'entrée. |
| **Action Principale (Marque)** | `indigo-600` | `#4f46e5` | `bg-indigo-600 hover:bg-indigo-700 text-white shadow-sm` | Boutons d'action majeurs (*Démarrer Live Coding*, validation filtres). |
| **Accent & Focus** | `indigo-50` / `indigo-700` | `#eef2ff` / `#4338ca` | `bg-indigo-50 text-indigo-700 border-indigo-200` | Onglets actifs, pastilles d'apprenant en cours d'évaluation. |
| **Optimal / Succès** | `emerald-500` / `emerald-700` | `#10b981` / `#047857` | `bg-emerald-50 text-emerald-700 border-emerald-200` | Gating validé au 1er essai, $\text{ICP} \ge 90\%$, note optimale. |
| **Avertissement / Révision** | `amber-500` / `amber-700` | `#f59e0b` / `#b45309` | `bg-amber-50 text-amber-700 border-amber-200` | Statut *Besoin de révision*, charge élevée en file FIFO. |
| **Alerte Critique** | `rose-500` / `rose-700` | `#ef4444` / `#b91c1c` | `bg-rose-50 text-rose-700 border-rose-200` | Seuil 10 min dépassé post-Live Coding, divergence IA $\Delta > 5$ pts. |
| **File FIFO / Neutre** | `sky-500` / `sky-700` | `#0ea5e9` / `#0369a1` | `bg-sky-50 text-sky-700 border-sky-200` | Apprenant en attente de passage, statut de cohorte intermédiaire. |

---

### 2.3. Formes, Rayons & Élévations
- **Rayons de courbure (Border Radius)** :
  - `rounded-xl` (12px) : Boutons, contrôles d'entrée, sélecteurs, conteneurs d'icônes.
  - `rounded-2xl` (16px) : Cartes KPI, blocs de section, consoles opérationnelles.
  - `rounded-full` (9999px) : Badges d'état, pastilles de statut, avatars.
- **Élévations & Ombres (Box Shadows)** :
  - `shadow-xs` : Boutons secondaires et étiquettes.
  - `shadow-sm` : Cartes KPI par défaut, conteneurs de listes.
  - `hover:shadow-md transition` : État interactif des cartes au survol.

### 2.4. Structure de Disposition (Layout avec Sidebar Rétractable & Paramétrable)
Les écrans de tableau de bord adoptent l'architecture ergonomique à deux colonnes avec possibilité de masquer/afficher la barre latérale :
- **Sidebar Latérale Gauche (`aside#app-sidebar`)** :
  - Largeur standard : `w-64` avec transition fluide (`transition-all duration-300`).
  - Ancrage et défilement : `sticky top-0 h-screen overflow-y-auto bg-white border-r border-slate-200`.
  - **Paramétrable / Rétractable** :
    - Présence d'un bouton de repli dans le header de l'aside et d'un bouton hamburger dans la topbar.
    - Appel de la fonction minimale `toggleSidebar()` masquant la barre (`hidden`), ce qui permet au contenu principal d'occuper immédiatement 100% de la largeur de l'écran pour un confort visuel maximal.
  - Contenu : Logo officiel (`h-16`), carte de profil contextuelle au persona, liens de navigation verticaux avec icônes SVG et badges de compteurs, widget de pied (présence, charge logistique).
- **Zone de Contenu Principale (`div.flex-1`)** :
  - Défilement fluide, topbar d'en-tête synchronisée (`h-16 border-b border-slate-200`) avec bouton de rappel du menu, grille de KPI aérée et pied de page institutionnel.

---

## 🧩 3. Catalogue des Composants UI de Base

### 3.1. Badges & Statuts Métier
Les badges associent un code couleur sémantique, une bordure douce et une icône SVG vectorielle pour une identification visuelle instantanée :

```html
<!-- Validé au 1er essai -->
<span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-full text-xs font-bold bg-emerald-50 text-emerald-700 border border-emerald-200">
    <svg class="w-3.5 h-3.5 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"></path></svg>
    Gating Validé (Essai 1)
</span>

<!-- En file d'attente FIFO -->
<span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-full text-xs font-bold bg-sky-50 text-sky-700 border border-sky-200">
    <span class="w-2 h-2 rounded-full bg-sky-500 animate-pulse"></span>
    En file FIFO (Rang #2)
</span>

<!-- Besoin de révision -->
<span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-full text-xs font-bold bg-amber-50 text-amber-700 border border-amber-200">
    <svg class="w-3.5 h-3.5 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
    Besoin de Révision
</span>

<!-- Alerte Seuil 10 min -->
<span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-full text-xs font-bold bg-rose-50 text-rose-700 border border-rose-200">
    <span class="w-2 h-2 rounded-full bg-rose-500"></span>
    Bloqué &gt; 10 min
</span>
```

### 3.2. Boutons d'Action Contextuels
- **Primaire (Marque SoliLMS)** : `bg-indigo-600 hover:bg-indigo-700 text-white font-bold rounded-xl shadow-sm`.
- **Secondaire / Alternatif** : `bg-white hover:bg-slate-50 text-slate-700 border border-slate-300 font-semibold rounded-xl shadow-xs`.
- **Action de Remédiation / Assistance** : `bg-rose-50 hover:bg-rose-100 text-rose-700 border border-rose-200 font-bold rounded-xl`.
- **Action Rapide de Validation** : `bg-emerald-600 hover:bg-emerald-700 text-white font-bold rounded-lg shadow-xs`.

### 3.3. Jauges & Progressions Segmentées
- Hauteur standardisée à `h-2.5` avec fond `bg-slate-100` et arrondis intégraux (`rounded-full`).
- Découpage multi-segments pour la vue d'ensemble du sprint :
  - Segment validé : `bg-emerald-500`
  - Segment en cours / révision : `bg-amber-400`
  - Segment non entamé : `bg-slate-200`

---

## 📊 4. Composants Organismes du Tableau de Bord

### 4.1. KPI Stat Card Réactive
Chaque indicateur de l'Annexe M est encapsulé dans une carte structurée comprenant :
1. **En-tête** : Libellé de l'indicateur + badge de variation relative ($\pm \%$ comparé à la moyenne de cohorte).
2. **Corps principal** : Valeur chiffrée majeure (`text-3xl font-extrabold`) accompagnée du symbole mathématique officiel entre parenthèses.
3. **Didactique & Contexte** : Courte phrase explicative de la formule et de son impact pédagogique.
4. **Pied de carte** : Seuil d'alerte critique défini (vert / orange / rouge) ou effectif concerné.

### 4.2. Console de Régulation FIFO (Live Coding)
La console temps réel permet au formateur d'arbitrer les passages de prototype en un clic :
- **Rang d'arrivée strict** (`#1`, `#2`, `#N`).
- **Avatar & Statut visuel de présence**.
- **Chronomètre en direct** : Compteur dynamique `min:sec` alimenté par un intervalle régulier. Dès que le délai dépasse 15 min, la couleur passe en `rose-600`.
- **Boutons contextuels selon l'état** :
  - État *En attente* $\rightarrow$ Bouton `Démarrer Live`.
  - État *En cours* $\rightarrow$ Boutons `Valider Gating` (succès) ou `Révision` (ajournement).
  - Mode groupé $\rightarrow$ Bouton `Session Groupe (4x4)` actif dès que $\ge 4$ apprenants sont en attente.

### 4.3. Flashbox Alerte "Seuil 10 min Post-Live Coding"
Composant d'alerte haute visibilité positionné au-dessus des consoles :
- Encadrement à fort contraste : `bg-rose-50 border-2 border-rose-200 rounded-2xl`.
- Icône clignotante (`animate-pulse`) et badge `Action Requise`.
- Liste nominative des apprenants bloqués avec leur durée de dépassement (`+14 min`, `+11 min`).
- Bouton d'action directe : *"Assigner Trinôme d'Entraide"*.

### 4.4. Scatter Plot & Détection d'Écart IA (Pearson r)
Visualisation SVG vectorielle représentant la corrélation entre :
- Axe $X$ : Note du Prototype surveillé en direct ($N2$).
- Axe $Y$ : Note du Projet fil rouge assisté par IA ($N3$).
- **Ligne idéale** : $y = x$ (pointillés).
- **Zone de suspicion** : Zone colorée $N3 \gg N2$. Tout apprenant dont l'écart $\Delta = N3 - N2 > +5$ points est identifié par un point rouge clignotant avec infobulle pour convocation devant le jury.

---

## ⚡ 5. Spécifications Techniques Alpine.js

L'état réactif de la charte est orchestré via un composant racine `x-data="designSystemApp()"` :
- **État `fifoList`** : Tableau dynamique d'objets apprenants avec `id`, `nom`, `avatar`, `projet`, `essai`, `status`, `secondes`.
- **Minuterie active** : Boucle `setInterval` de 1 seconde incrémentant automatiquement les compteurs d'attente.
- **Fonctions d'action** : `demarrerPassage(id)`, `validerPassage(id)`, `ajournerPassage(id)`, `supprimer(id)`, `ajouterApprenant()`.
- **Zéro rafraîchissement de page** : Les transitions de file et validations s'effectuent sans rechargement.

---

## 📂 6. Fichiers Associés

- **Démonstrateur Interactif Navigateur** : [charte-graphique/index.html](file:///c:/AppServer/solicode-lms/cahiers-charges/PkgTableauBord/charte-graphique/index.html)
- **Feuille de Route & Checklist Phase 2** : [Plan-Phase-2-Charte-Graphique.md](file:///c:/AppServer/solicode-lms/cahiers-charges/PkgTableauBord/Plan-Phase-2-Charte-Graphique.md)
- **Spécifications des 9 Indicateurs** : [Dossier indicateurs/](file:///c:/AppServer/solicode-lms/cahiers-charges/PkgTableauBord/indicateurs/)
