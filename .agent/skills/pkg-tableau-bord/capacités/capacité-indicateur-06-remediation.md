# Capacité : Indicateur 06 — Détection des Ruptures & Remédiations ($T_{\text{intervention}}$)

## 1. Fiche Synthétique

| Paramètre | Spécification Didactique & Technique |
| :--- | :--- |
| **Numéro & Symbole** | **Indicateur 06** — $T_{\text{intervention}}$ |
| **Nom Officiel** | Taux d'intervention en remédiation & Résolution des blocages |
| **Axe Pédagogique** | H2 — Prise en charge chirurgicale des décrochages post-démonstration |
| **Condition Déclenchante** | **Présence impérative d'un démonstrateur (`is_live_coding = 1`)** |
| **Fenêtre d'Autonomie** | **30 minutes** après la fin du Live Coding démonstrateur ($t_0$) |
| **Statut BDD Déclenché** | **`EN_REMEDIATION` (ID 11)** + Fiche créée dans table `remediations` |
| **Ergonomie Dashboard** | **Affichage exclusif des apprenants en remédiation (sans popup modale)** |
| **Service / Trait** | `Modules\PkgTableauBord\Services\Traits\DashboardIndicateur06Trait` |
| **Route & Page** | `admin.PkgTableauBord.indicateurs.intervention` (`indicateurs/intervention.blade.php`) |
| **Composant KPI** | `PkgTableauBord::components.kpi-intervention` |

---

## 2. Modélisation Didactique & Workflow Automatisé

### A. Condition d'Existence Obligatoire (`is_live_coding = 1`)
Pour qu'une tâche soit éligible à la remédiation, **il DOIT impérativement exister au moins une réalisation de cette tâche ayant `is_live_coding = 1`** (l'apprenant démonstrateur désigné pour le Live Coding au tableau). Si aucune réalisation d'une tâche ne possède `is_live_coding = 1`, le système ne doit **JAMAIS** basculer la tâche en remédiation.

### B. Fin de la Démonstration ($t_0$) & Chronomètre d'Autonomie
1. L'apprenant démonstrateur termine sa démonstration et passe sa tâche à l'état **« À valider Live coding »** (`A_VALIDER_LIVE_CODING` - ID 12) ou est validé par le formateur (`APPROVED` / `VALIDEE`).
2. Cette transition fige l'horodatage de référence $t_0$.
3. Les pairs en classe bénéficient alors d'une **fenêtre d'autonomie cible de 30 minutes** (`seuil_blocage_minutes = 30` par défaut dans `affectation_projets`).

### C. Bascule Automatique en `EN_REMEDIATION`
Dès que $now > t_0 + 30\text{ min}$ :
- Toute réalisation non achevée (`TODO`, `IN_PROGRESS`, `PAUSED`, `REVISION_NECESSAIRE`) des pairs (`is_live_coding = 0`) de cette même tâche bascule automatiquement à l'état **`EN_REMEDIATION` (ID 11)**.
- **Règle absolue** : Une tâche à l'état **« Non validée » (`NOT_VALIDATED`) ne bascule JAMAIS en remédiation**.
- Le système insère simultanément un enregistrement dans la table **`remediations`** (`est_resolu = false`, `date_debut = now()`).

### D. Résolution Directe en Classe (`est_resolu = true`)
1. Le formateur intervient directement auprès des apprenants bloqués.
2. Aucune typologie artificielle (*"Trinôme"*, *"Atelier"*, *"Solo"*) n'est imposée par le système, et aucune popup modale ne s'affiche au chargement.
3. Dès que l'apprenant surmonte le blocage, le formateur passe la tâche de `EN_REMEDIATION` à `VALIDEE` (ou `APPROVED`).
4. L'événement déclenche automatiquement la mise à jour de la fiche dans `remediations` (`est_resolu = true`, `date_fin = now()`). L'apprenant quitte immédiatement la liste active.

---

## 3. Formules Mathématiques

### Taux d'Autonomie Initiale (Pérenne) :
$$\tau_{\text{autonomie}} = \frac{N_{\text{total}} - N_{\text{remédiés}}}{N_{\text{total}}} \times 100$$
*(Reste mémorisé même après que les remédiations ont été résolues).*

### Taux de Résolution des Remédiations :
$$\tau_{\text{résolution}} = \frac{N_{\text{résolus}}}{N_{\text{remédiés}}} \times 100$$
*(Atteint $100\%$ dès que tous les apprenants remédiés ont validé leur tâche).*

---

## 4. Composants & Ergonomie 100% Inline

- **Carte KPI** (`PkgTableauBord::components.kpi-intervention`) :
  - Formateur : Nombre d'apprenants bloqués actuellement en attente d'aide, taux d'autonomie et statut.
  - Responsable : Taux d'intervention moyen et volume de soutien actif.
- **Page de Détail** (`admin.PkgTableauBord.indicateurs.intervention`) :
  - **Tableau des Alertes Actives** : Affiche **exclusivement** les apprenants dont la tâche est en état `EN_REMEDIATION`.
  - **Mémoire Pédagogique (Résolues)** : Liste des interventions passées menées à bien.
  - Zéro modale bloquante au chargement, 100% inline.
