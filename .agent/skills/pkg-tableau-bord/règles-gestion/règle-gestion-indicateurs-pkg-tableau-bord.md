# Règle de Gestion : Spécification des Indicateurs (Sprints 1 & 2)

Ce document détaille la modélisation mathématique, les seuils d'alerte et les algorithmes des indicateurs développés dans le module `PkgTableauBord`.
Les indicateurs des sprints ultérieurs (3 à 9) seront intégrés de façon itérative.

---

## ⏱️ Indicateur 01 : Démarrage du Travail ($T_{\text{amorçage}}$) — Sprint 1

### 1. Fiche Synthétique
| Paramètre | Valeur |
| :--- | :--- |
| **N° & Symbole** | **01** — $T_{\text{amorçage}}$ |
| **Nom de l'Indicateur** | Temps d'amorçage moyen |
| **Axe Pédagogique** | H1 — Régulation de l'Apprenant & Rythme de Démarrage |
| **Unité** | Minutes / Heures (ex: `1h15`) |
| **Seuil Vert (Optimal)** | $\le 1{,}5\,\text{h}$ ($90\text{ min}$) |
| **Seuil Rouge (Alerte)** | $> 3{,}0\,\text{h}$ ($180\text{ min}$) |

### 2. Formule Mathématique
- **Individuel** : $T_{\text{amorçage}}(i) = \text{cache\_date\_premiere\_soumission}(i) - \text{cache\_date\_lancement}$
- **Collectif** : $T_{\text{amorçage}} = \frac{1}{N} \sum_{i=1}^{N} T_{\text{amorçage}}(i)$
- **Déclencheur** : Première tâche soumise (`TO_APPROVE` / `READY_FOR_LIVE_CODING`) $\rightarrow$ fige la date dans `realisation_projets.cache_date_premiere_soumission`.

---

## 🛡️ Indicateur 02 : Validation du Prototype au Premier Essai ($R_{\text{gating}}$) — Sprint 2

### 1. Fiche Synthétique
| Paramètre | Valeur |
| :--- | :--- |
| **N° & Symbole** | **02** — $R_{\text{gating}}$ |
| **Nom de l'Indicateur** | Taux de validation au gating dès le premier essai |
| **Axe Pédagogique** | H1 — Assimilation des fondamentaux et prévention de l'illusion de maîtrise |
| **Unité** | Pourcentage ($0 \le R_{\text{gating}} \le 100\%$) |
| **Seuil Vert (Excellence)** | $\ge 80\,\%$ |
| **Seuil Orange (Vigilance)** | $65\,\% \le R_{\text{gating}} < 80\,\%$ |
| **Seuil Rouge (Alerte)** | $< 65\,\%$ (Illusion d'acquis structurelle, remédiation impérative) |

### 2. Formules Mathématiques

#### A. Micro (Individuel — Apprenant $i$) :
$$\text{Gating}_1(i) = \begin{cases} 
1 & \text{si validé dès la tentative 1 (statut } \text{APPROVED} \text{ ou note } \ge 10) \\
0 & \text{si ajourné à la tentative 1 (statut } \text{NOT\_VALIDATED} \text{ / Révision)}
\end{cases}$$
- Si ajourné, l'apprenant passe en atelier de remédiation et effectue une seconde tentative ($\text{tentative} = 2$), mais son statut $\text{Gating}_1(i)$ reste définitivement fixé à $0$.

#### B. Méso (Collectif — Promotion / Groupe) :
$$R_{\text{gating}} = \frac{N_{\text{validés\_essai\_1}}}{N_{\text{évalués}}} \times 100$$
- $N_{\text{validés\_essai\_1}}$ : Effectif des apprenants ayant réussi dès la 1ère évaluation.
- $N_{\text{évalués}}$ : Effectif total ayant subi au moins une évaluation de prototype.

#### C. Macro (Établissement — Gouvernance) :
$$R_{\text{gating\_macro}} = \frac{\sum_{g=1}^{G} N_{\text{validés\_essai\_1}}(g)}{\sum_{g=1}^{G} N_{\text{évalués}}(g)} \times 100$$

### 3. Algorithme Événementiel (Temps Réel)
1. Détection de la tâche Prototype : Une tâche est de type Prototype si et seulement si sa phase d'évaluation liée est Niveau 2 (`tache->phaseEvaluation->code === 'N2'`).
2. Le formateur évalue cette tâche Prototype (`RealisationTache`).
3. Interception par `RealisationTacheJobTrait::updatedObserverJob`.
4. Incrémentation de `realisationProjet->cache_nombre_tentatives_prototype`.
5. Si `tentative == 1` :
   - Fige `realisationProjet->cache_est_valide_prototype_premier_essai = ($estValide ? true : false)`.
6. Si `tentative > 1` :
   - Maintient la valeur initiale (garantie d'idempotence docimologique).
