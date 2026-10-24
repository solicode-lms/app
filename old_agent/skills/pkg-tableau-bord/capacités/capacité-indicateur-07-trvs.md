# Capacité : Indicateur 07 — Réalisation Validée & Impact Live Coding ($\text{TRVS}$)

## 1. Fiche Synthétique

| Paramètre | Spécification Didactique & Technique |
| :--- | :--- |
| **Numéro & Symbole** | **Indicateur 07** — $\text{TRVS}$ |
| **Nom Officiel** | Taux de Réalisation Validée par Session / Sprint & Impact du Live Coding |
| **Axe Pédagogique** | H4 — Validation effective des livrables et mesure empirique du gain didactique |
| **Critère d'État Strict** | **Seul le statut `DONE` (Validé) est comptabilisé comme achevé** |
| **Unité & Format** | Pourcentage ($0 \le \text{TRVS} \le 100\%$) et gain $\Delta \text{TRVS}$ en points |
| **Seuil Vert (Optimal)** | $\ge 80\,\%$ de réalisations validées |
| **Seuil Orange (Vigilance)**| $65\,\% \le \text{TRVS} < 80\,\%$ |
| **Seuil Rouge (Alerte)** | $< 65\,\%$ (Déficit significatif d'achèvement) |
| **Service / Trait** | `Modules\PkgTableauBord\Services\Traits\DashboardIndicateur07Trait` |
| **Route & Page** | `admin.PkgTableauBord.indicateurs.trvs` (`indicateurs/trvs.blade.php`) |
| **Composant KPI** | `PkgTableauBord::components.kpi-integrite-ia` |

---

## 2. Modélisation Didactique & Mesure d'Impact

### A. Règle d'État Stricte
- Seuls les projets ayant atteint le statut **Validé** (`etats_realisation_projets.code = 'DONE'`) sont comptabilisés dans le numérateur $N_{\text{validées}}$.
- Les projets en état **« À valider » (`TO_APPROVE`)** (terminés en attente de soutenance) et **« En cours » (`IN_PROGRESS`)** sont intégrés dans le dénominateur $N_{\text{attendues}}$, mais **ne sont pas validés** tant que l'évaluation finale n'a pas été prononcée.

### B. Mesure Empirique d'Impact du Live Coding ($\Delta \text{TRVS}$)
L'indicateur confronte deux cohortes méthodologiques :
1. **Sessions Témoins (Avant Live Coding synchrone)** : Sessions menées en autonomie asynchrone classique.
2. **Sessions Actives (Après Live Coding synchrone)** : Sessions intégrant les démonstrations au tableau et soutenances directes.
$$\Delta \text{TRVS} = \text{TRVS}_{\text{après}} - \text{TRVS}_{\text{avant}}$$
Exemple réel de gain mesuré : passage de $62{,}5\%$ à $80{,}0\%$ ($\Delta = +17{,}5\text{ pts}$).

---

## 3. Formules Mathématiques

### Taux de Réalisation Validée :
$$\text{TRVS} = \frac{N_{\text{validées}}}{N_{\text{attendues}}} \times 100$$

### Évolution Chronologique :
Suivi séquentiel session par session ($S_1 \to S_2 \to S_3$) attestant de la progression de maturité de la promotion.

---

## 4. Composants & Ergonomie 100% Inline

- **Carte KPI** (`PkgTableauBord::components.kpi-integrite-ia`) :
  - Formateur : Taux TRVS actuel, ratio $N_{\text{validées}} / N_{\text{attendues}}$, badge de conformité.
  - Responsable : Taux macroscopique, comparatif Avant vs Après et gain net en points.
- **Page de Détail** (`admin.PkgTableauBord.indicateurs.trvs`) :
  - 4 cartes KPI directes (Taux TRVS, Validés, En attente de soutenance, Gain $\Delta \text{TRVS}$).
  - Tableau comparatif méthodologique Avant vs Après.
  - Frise chronologique d'évolution des sessions.
  - Roster individuel complet des apprenants sous Alpine.js.
