# Commande Artisan : `dashboard:recalculer-gating`

> **Module** : PkgTableauBord / PkgRealisationProjets  
> **Indicateur** : Indicateur 02 — Validation du Prototype / Gating ($R_{\text{gating}}$)  
> **Classe** : `App\Console\Commands\RecalculerGatingCommand`

---

## 1. Objectif

Cette commande Artisan permet de recalculer et d'initialiser les colonnes dénormalisées de cache relatives au taux de validation du prototype dès le premier essai ($R_{\text{gating}}$) :
- `cache_nombre_tentatives_prototype` sur la table `realisation_projets`.
- `cache_est_valide_prototype_premier_essai` sur la table `realisation_projets`.
- `cache_date_validation_prototype` sur la table `realisation_projets`.

---

## 2. Définition Métier du Prototype

> [!IMPORTANT]
> Une tâche est formellement identifiée comme **épreuve de type Prototype** si et seulement si sa phase d'évaluation associée est de niveau 2 (**N2** — *Adaptation / Prototype contextualisé*) via `$realisationTache->isPrototype()` ou `tache->phaseEvaluation->code === 'N2'`.

---

## 3. Quand exécuter cette commande ?

1. **Après le déploiement initial du Sprint 2** : Pour reconstituer le passif des évaluations de Live Coding Niveau 2.
2. **Après une restauration ou import de base de données** : Pour peupler les colonnes `cache_` sur les historiques existants.
3. **Maintenance périodique** : La commande est idempotente et préserve les résultats docimologiques du premier essai.

---

## 4. Commande d'exécution

```bash
# Rattrapage standard (idempotent, ne touche pas aux enregistrements déjà renseignés)
php artisan dashboard:recalculer-gating

# Rattrapage forcé (recalcule tout l'historique)
php artisan dashboard:recalculer-gating --force
```

---

## 5. Logique d'exécution

1. **Filtrage des tâches N2** : Ne prend en compte que les tâches rattachées à la phase d'évaluation `N2`.
2. **Détection des évaluations** : Repère les états `APPROVED`, `NOT_VALIDATED` et `REVISION_NECESSAIRE`.
3. **Analyse de l'historique (`HistoriqueRealisationTache`)** :
   - Si aucun rejet antérieur n'a précédé la validation $\rightarrow$ `cache_est_valide_prototype_premier_essai = true`, `tentatives = 1`.
   - Si un rejet/remise à faire a précédé la validation $\rightarrow$ `cache_est_valide_prototype_premier_essai = false`, `tentatives = rejets + 1`, date de validation enregistrée.
   - Si la tâche est ajournée/en remédiation $\rightarrow$ `cache_est_valide_prototype_premier_essai = false`, date de validation à `NULL`.
