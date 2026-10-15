# Capacité : Indicateur 08 — Couverture des Compétences Nationales ($\text{ICP}$)

## 1. Fiche Synthétique

| Paramètre | Spécification Didactique & Technique |
| :--- | :--- |
| **Numéro & Symbole** | **Indicateur 08** — $\text{ICP}$ |
| **Nom Officiel** | Progression des Compétences du Référentiel National (ICP Temporel) |
| **Axe Pédagogique** | H4 — Couverture didactique nationale & Éradication des faux positifs temporels |
| **Principe Fondateur** | Confrontation dynamique de `progression_cache` avec `progression_ideal_cache` |
| **Unité & Bornes** | Pourcentage ($0 \le \text{ICP} \le 100\%$) et Taux de Rythme Temporel |
| **Seuil Vert (Optimal)** | Rythme $\ge 95\%$ du calendrier idéal ($\Delta \ge -5\text{ pts}$) |
| **Seuil Orange (Vigilance)**| Rythme $80-95\%$ du calendrier idéal ($\Delta$ entre $-5$ et $-15\text{ pts}$) |
| **Seuil Rouge (Impasse)** | Calendrier $\ge 25\%$ ET (Rythme $< 60\%$ ou $\Delta < -25\text{ pts}$) |
| **Service / Trait** | `Modules\PkgTableauBord\Services\Traits\DashboardIndicateur08Trait` |
| **Route & Page** | `admin.PkgTableauBord.indicateurs.couverture` (`indicateurs/couverture.blade.php`) |
| **Composants KPI** | `PkgTableauBord::components.kpi-couverture-pedagogique`, `PkgTableauBord::components.impasses-pedagogiques-panel` |

---

## 2. Modélisation Didactique & Éradication des Faux Positifs

### A. Non-Faux Positif Temporel
- En début ou milieu d'année, un taux de validation brut $< 85\%$ est **parfaitement normal** et ne constitue pas une impasse si le calendrier pédagogique national ne prévoyait pas encore son achèvement.
- L'indicateur éradique les faux positifs en évaluant la vitesse d'assimilation réelle par rapport à la progression attendue à date :
  - `progression_cache` : Progression réelle moyenne constatée sur la cohorte.
  - `progression_ideal_cache` : Progression idéale programmée selon le calendrier pédagogique.

### B. Critère d'Impasse Didactique Réelle
Une compétence est qualifiée d'**impasse didactique** si et seulement si :
1. Le calendrier didactique est effectivement entamé : `progression_ideal_cache >= 25.0%`.
2. Le rythme d'acquisition de la cohorte présente un décrochage critique :
   $$\text{Taux Rythme} < 60{,}0\% \quad \text{OU} \quad \Delta = (\text{progression} - \text{progression\_idéale}) < -25{,}0\text{ pts}$$

---

## 3. Formules Mathématiques

### Progression Moyenne de la Cohorte :
$$\overline{\text{Prog}} = \frac{1}{|C|} \sum_{c \in C} \text{progression\_cache}(c)$$

### Taux de Rythme Temporel :
$$\text{Taux Rythme} = \frac{\overline{\text{Prog}}}{\overline{\text{Prog\_Idéale}}} \times 100$$

### Écart au Calendrier Idéal :
$$\Delta = \overline{\text{Prog}} - \overline{\text{Prog\_Idéale}}$$

---

## 4. Composants & Ergonomie 100% Inline

- **Carte KPI** (`PkgTableauBord::components.kpi-couverture-pedagogique`) :
  - Affiche l'ICP actuel, la comparaison avec l'idéal attendu, et le nombre d'impasses didactiques actives.
- **Panneau Formateur** (`PkgTableauBord::components.impasses-pedagogiques-panel`) :
  - Alerte ciblée sur les compétences présentant un décrochage temporel réel.
- **Page de Détail** (`admin.PkgTableauBord.indicateurs.couverture`) :
  - 4 cartes KPI directes (Taux ICP réel, Rythme temporel face à l'idéal, Impasses critiques, Homogénéité).
  - Navigation par 3 onglets interactifs sous Alpine.js :
    1. **Compétences Nationales** : Matrice macroscopique avec jauge réelle vs idéale et qualification de sévérité.
    2. **Micro-Compétences** : Détail fin des validations effectives à l'état `DONE`.
    3. **Grille Individuelle des Apprenants** : Vue apprenant par apprenant avec tri par rythme didactique.
  - Benchmark macroscopique inter-groupes.
