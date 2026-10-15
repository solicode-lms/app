# Capacité : Indicateur 05 — Régulation de la File d'Attente FIFO ($T_{\text{file}}$)

## 1. Fiche Synthétique

| Paramètre | Spécification Didactique & Technique |
| :--- | :--- |
| **Numéro & Symbole** | **Indicateur 05** — $T_{\text{file}}$ |
| **Nom Officiel** | Délai moyen d'attente en file FIFO & Régulation du flux de soutenance |
| **Axe Pédagogique** | H3 — Fluidité des passages, élimination des temps morts et arbitrage synchrone |
| **Périmètre Strict** | **Exclusivement les tâches Prototype / Live Coding Niveau 2 ($N2$)** |
| **Durée Épreuve Solo** | **40 à 60 minutes** par apprenant |
| **Régulation Saturation** | **Session mutualisée 4x4 Discord** (mosaïque 4 flux vidéo sur 45-60 min) |
| **Seuil Vert (Fluidité)** | $T_{\text{file}} \le 30\text{ min}$ (ou $< 3$ apprenants en attente) |
| **Seuil Orange (Vigilance)**| $30\text{ min} < T_{\text{file}} \le 60\text{ min}$ (ou $3$ à $4$ apprenants en attente) |
| **Seuil Rouge (Saturation)**| $T_{\text{file}} > 60\text{ min}$ ou $\ge 5$ apprenants en attente $\implies$ bascule Discord 4x4 |
| **Service / Trait** | `Modules\PkgTableauBord\Services\Traits\DashboardIndicateur05Trait` |
| **Route & Page** | `admin.PkgTableauBord.indicateurs.file-attente` (`indicateurs/file-attente.blade.php`) |
| **Composant KPI** | `PkgTableauBord::components.kpi-file-attente` |

---

## 2. Modélisation Didactique & Cycle FIFO

### A. Entrée en File d'Attente
1. Dès que l'apprenant termine l'implémentation de son prototype N2, la tâche passe à l'état **« Prêt pour Live Coding »** (`READY_FOR_LIVE_CODING`).
2. Cet événement fige l'horodatage `cache_date_entree_file = now()`.
3. L'apprenant voit en direct son rang dans la file (ex : `Rang #2`, temps estimé `40-60 min`).

### B. Prise en Charge & Calcul du Délai
1. Le formateur appelle l'apprenant suivant depuis sa console ordonnée en FIFO strict (`cache_date_entree_file ASC`).
2. La tâche passe à l'état **« En Live Coding »** (`IN_LIVE_CODING`), ce qui horodate `cache_date_debut_live_coding = now()`.
3. Le délai d'attente effectif est calculé :
   $$T_{\text{attente}} = \text{diffInMinutes}(\text{cache\_date\_debut\_live\_coding}, \text{cache\_date\_entree\_file})$$
   *(Si l'appel est immédiat, la valeur `0 min` est conservée sans distorsion).*

### C. Régulation du Goulet d'Étranglement : Session Mutualisée 4x4 Discord
En cas de saturation de la file ($\ge 5$ apprenants ou attente $> 60\text{ min}$) :
- Le formateur active une **session mutualisée 4x4 via Discord**.
- 4 apprenants partagent leur écran en direct simultanément pendant **40 à 60 minutes** (durée collective équivalente à une soutenance solo).
- Le formateur observe un **écran mosaïque à 4 flux vidéo** et peut alterner entre une focalisation plein écran et une supervision collective.
- Le débit d'absorption est ainsi quadruplé pour résorber l'embouteillage sans dégrader le niveau d'exigence.

---

## 3. Formules Mathématiques

### Micro (Apprenant $i$) :
$$T_{\text{file}}(i) = \text{cache\_delai\_attente\_minutes}(i)$$

### Méso (Promotion / Groupe) :
$$\overline{T}_{\text{file}} = \frac{1}{|E|} \sum_{i \in E} T_{\text{file}}(i)$$
- $E$ : Ensemble des apprenants ayant été pris en charge ou actuellement en attente.

---

## 4. Composants & Ergonomie 100% Inline

- **Carte KPI** (`PkgTableauBord::components.kpi-file-attente`) :
  - Apprenant : Rang dans la file, temps d'attente écoulé et estimation avant passage.
  - Formateur : Délai moyen, effectif en attente et alerte de saturation.
- **Console Opérationnelle** (`admin.PkgTableauBord.indicateurs.file-attente`) :
  - File d'attente réactive en direct ordonnée par ancienneté.
  - Bouton d'appel individuel (passage en Live Coding solo).
  - Bouton d'activation de session mutualisée Discord 4x4.
  - Historique des passages de la journée.
