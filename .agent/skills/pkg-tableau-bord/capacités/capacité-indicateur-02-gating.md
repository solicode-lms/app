# Capacité : Indicateur 02 — Validation du Prototype au 1er Essai ($R_{\text{gating}}$)

## 1. Fiche Synthétique

| Paramètre | Spécification Didactique & Technique |
| :--- | :--- |
| **Numéro & Symbole** | **Indicateur 02** — $R_{\text{gating}}$ |
| **Nom Officiel** | Taux de validation au gating dès le premier essai |
| **Axe Pédagogique** | H1 — Assimilation des fondamentaux et prévention de l'illusion de maîtrise |
| **Unité & Bornes** | Pourcentage ($0 \le R_{\text{gating}} \le 100\%$) |
| **Seuil Vert (Excellence)** | $\ge 80\,\%$ |
| **Seuil Orange (Vigilance)** | $65\,\% \le R_{\text{gating}} < 80\,\%$ |
| **Seuil Rouge (Alerte)** | $< 65\,\%$ (Illusion d'acquis structurelle, remédiation didactique impérative) |
| **Service / Trait** | `Modules\PkgTableauBord\Services\Traits\DashboardIndicateur02Trait` |
| **Route & Page** | `admin.PkgTableauBord.indicateurs.gating` (`indicateurs/gating.blade.php`) |
| **Composant KPI** | `PkgTableauBord::components.kpi-gating` |

---

## 2. Modélisation Didactique & Cycle de Gating

### A. Identification de la Tâche Prototype
- Une tâche est de type **Prototype** si et seulement si sa phase d'évaluation liée correspond au **Niveau 2 ($N2$ — Adapter)** :
  `tache->phaseEvaluation->code === 'N2'` (ou méthode `$tache->isPrototype()`).
- Le prototype constitue le jalon de validation conditionnant l'accès au Projet Fil Rouge ($N3$).

### B. Idempotence Docimologique du Premier Essai
Lors de l'évaluation de la tâche Prototype par le formateur :
1. `RealisationTacheJobTrait::updatedObserverJob` intercepte l'évaluation.
2. Incrémentation de `realisationProjet->cache_nombre_tentatives_prototype`.
3. **Si `tentative == 1`** :
   - Fige la variable `realisationProjet->cache_est_valide_prototype_premier_essai` (`true` si `APPROVED`, `false` sinon).
4. **Si `tentative > 1` (Remédiation)** :
   - L'apprenant effectue sa deuxième tentative après étayage, mais la valeur du premier essai reste **strictement conservée à `false`**.
   - Garantit l'idempotence docimologique de l'indicateur.

---

## 3. Formules Mathématiques

### Micro (Apprenant individuel $i$) :
$$\text{Gating}_1(i) = \begin{cases} 
1 & \text{si validé dès la tentative 1 (statut } \text{APPROVED} \text{ ou note } \ge 10) \\
0 & \text{si ajourné à la tentative 1 (statut } \text{NOT\_VALIDATED} \text{ / Révision)}
\end{cases}$$

### Méso (Promotion / Groupe / Session) :
$$R_{\text{gating}} = \frac{N_{\text{validés\_essai\_1}}}{N_{\text{évalués}}} \times 100$$
- $N_{\text{validés\_essai\_1}}$ : Nombre d'apprenants ayant réussi leur prototype dès le 1er passage.
- $N_{\text{évalués}}$ : Effectif total ayant subi au moins une évaluation de prototype.

### Macro (Établissement / Gouvernance) :
$$R_{\text{gating\_macro}} = \frac{\sum_{g=1}^{G} N_{\text{validés\_essai\_1}}(g)}{\sum_{g=1}^{G} N_{\text{évalués}}(g)} \times 100$$

---

## 4. Composants & Ergonomie 100% Inline

- **Carte KPI** (`PkgTableauBord::components.kpi-gating`) :
  - Apprenant : Statut personnel (`Validé 1er essai`, `En remédiation`, `En attente`) + Droit d'accès au projet fil rouge N3 (`Débloqué` vs `Verrouillé`).
  - Formateur : Taux de réussite au 1er passage ($R_{\text{gating}}\%$), ratio chiffré `$valides / $evalues`, apprenants en remédiation.
  - Responsable : Taux macroscopique et comparaison inter-promotions.
- **Page de Détail** (`admin.PkgTableauBord.indicateurs.gating`) :
  - Métriques clés et jauges de succès au premier essai.
  - Liste des apprenants en attente de deuxième passage (remédiation).
  - Benchmark comparatif des promotions avec badges didactiques (`Excellence`, `Vigilance`, `Alerte`).
