# Commande Artisan : `dashboard:recalculer-amorcage`

> **Module** : PkgTableauBord / PkgRealisationProjets  
> **Indicateur** : Indicateur 01 — Démarrage du travail ($T_{\text{amorçage}}$)  
> **Classe** : `App\Console\Commands\RecalculerAmorcageCommand`

---

## 1. Objectif

Cette commande Artisan permet de recalculer et d'initialiser les colonnes dénormalisées de cache relatives au délai d'amorçage ($T_{\text{amorçage}}$) :
- `cache_date_lancement` sur la table `affectation_projets`.
- `cache_date_lancement` sur la table `realisation_projets`.
- `cache_date_premiere_soumission` sur la table `realisation_projets`.

---

## 2. Quand exécuter cette commande ?

Le développeur ou l'administrateur système peut exécuter cette commande dans les cas suivants :

1. **Après le déploiement initial du Sprint 1** : Pour initialiser les métriques sur les cohortes et projets déjà existants en base.
2. **Après une restauration ou un import BDD** : Si des projets importés possèdent des colonnes `cache_` à `NULL`.
3. **En cas de désynchronisation** : Si les indicateurs d'amorçage des apprenants ou formateurs affichent des valeurs manquantes.
4. **Maintenance périodique** : La commande est idempotente et peut être réexécutée sans risque d'écraser des données valides.

---

## 3. Commande d'exécution

```bash
php artisan dashboard:recalculer-amorcage
```

---

## 4. Logique d'exécution

La commande effectue séquentiellement les opérations suivantes :

1. **Calcul de `cache_date_lancement` (`affectation_projets`)** :
   - Invoque `AffectationProjetService::determinerDateLancement()`.
   - Si des tâches ont déjà été démarrées par la cohorte, prend la date la plus ancienne de mise en cours (`IN_PROGRESS`).
   - Sinon, fige la date de début de l'affectation à 09:00:00.

2. **Synchronisation de `cache_date_lancement` (`realisation_projets`)** :
   - Recopie la date de lancement déterminée sur chaque réalisation de projet liée.

3. **Reconstitution de `cache_date_premiere_soumission` (`realisation_projets`)** :
   - Inspecte l'`HistoriqueRealisationTache` des tâches du projet pour repérer le premier horodatage de passage à `TO_APPROVE`, `READY_FOR_LIVE_CODING` ou `APPROVED`.
   - Si aucune transition d'état n'a été historisée mais que des tâches sont actuellement soumises ou validées, utilise l'horodatage `updated_at`.
   - Si le projet n'a aucune tâche soumise, le champ reste `NULL` (en attente du premier travail de l'apprenant).

---

## 5. Règle d'Idempotence

- **Idempotence garantie** : Si une réalisation possède déjà une `cache_date_premiere_soumission` non nulle, la commande préserve la valeur existante et ne la remplace pas.
