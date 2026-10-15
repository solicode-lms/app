# Capacité : Indicateur 03 — Boucles de Révision & Qualité du Code ($F_{\text{révision}}$)

## 1. Fiche Synthétique

| Paramètre | Spécification Didactique & Technique |
| :--- | :--- |
| **Numéro & Symbole** | **Indicateur 03** — $F_{\text{révision}}$ |
| **Nom Officiel** | Fréquence des boucles de révision & Taux conforme 1er jet |
| **Axe Pédagogique** | H2 — Clarté des consignes, stabilité du code et détection des tâches ambiguës |
| **Unité & Format** | Décimale (ex : `0.8 rep/tâche`, `1.4 rep/tâche`) et pourcentage de premier jet |
| **Seuil Vert (Fluide)** | $F_{\text{révision}} \le 1{,}2$ reprises par tâche ($\ge 75\%$ conforme 1er jet) |
| **Seuil Orange (Modéré)** | $1{,}2 < F_{\text{révision}} \le 2{,}0$ reprises par tâche ($50-75\%$ conforme 1er jet) |
| **Seuil Rouge (Alerte)** | $F_{\text{révision}} > 2{,}0$ reprises par tâche (Instabilité critique / Consignes ambiguës) |
| **Service / Trait** | `Modules\PkgTableauBord\Services\Traits\DashboardIndicateur03Trait` |
| **Route & Page** | `admin.PkgTableauBord.indicateurs.revisions` (`indicateurs/revisions.blade.php`) |
| **Composant KPI** | `PkgTableauBord::components.kpi-revision` |

---

## 2. Modélisation Didactique & Événements de Révision

### A. Comptabilisation des Rejets de Code
1. Lorsqu'un formateur refuse un livrable ou demande des corrections, la tâche passe à l'état **« Révision nécessaire »** (`REVISION_NECESSAIRE`).
2. Cet événement incrémente le compteur `realisationTache->cache_nombre_revisions`.
3. Une tâche est dite **« Conforme 1er jet »** si et seulement si elle a atteint l'état validé sans jamais être passée par `REVISION_NECESSAIRE` (`cache_nombre_revisions === 0`).

### B. Diagnostic Automatique : Top 3 des Tâches à Réaligner
Le système analyse l'ensemble des tâches de la session et agrège le nombre total de rejets par tâche générique (`Tache`). Il extrait pour le formateur le **Top 3 des tâches** concentrant le plus grand nombre de rejets, révélant :
- Des énoncés de consigne potentiellement ambigus ou incomplets.
- Des prérequis techniques non assimilés nécessitant un rappel magistral au tableau.

---

## 3. Formules Mathématiques

### Micro (Apprenant individuel $i$) :
$$F_{\text{révision}}(i) = \frac{\sum_{t \in \text{Tâches}(i)} \text{rejets}(t)}{|\text{Tâches}(i)|}$$
$$\tau_{\text{1er\_jet}}(i) = \frac{|\{t \in \text{Tâches}(i) \mid \text{rejets}(t) = 0 \land \text{est\_validée}(t)\}|}{|\text{Tâches}(i)|} \times 100$$

### Méso (Promotion / Groupe / Session) :
$$F_{\text{révision}} = \frac{\text{Total rejets sur l'ensemble des tâches}}{\text{Total tâches terminées/évaluées}}$$
$$\tau_{\text{conforme\_1er\_jet}} = \frac{\text{Nombre de réalisations de tâches sans rejet}}{\text{Total réalisations de tâches}} \times 100$$

### Macro (Établissement / Gouvernance) :
Agrégation multi-promotions comparant la fluidité didactique inter-groupes et inter-formateurs.

---

## 4. Composants & Ergonomie 100% Inline

- **Carte KPI** (`PkgTableauBord::components.kpi-revision`) :
  - Apprenant : Taux de conformité au premier jet personnel et moyenne de révisions.
  - Formateur : Fréquence collective ($F_{\text{révision}}$), pourcentage de premier jet, mention du Top 3 des tâches à réaligner, lien direct vers le détail.
  - Responsable : Benchmark inter-promotions de la fréquence des reprises.
- **Page de Détail** (`admin.PkgTableauBord.indicateurs.revisions`) :
  - Cartes métriques de fluidité et ratio de premier jet.
  - Panneau détaillé du Top 3 des tâches instables avec analyse des motifs de rejet.
  - Matrice complète des tâches par apprenant sous Alpine.js.
