# Règle de Gestion : Modèle de Données PkgTableauBord (Sprints 1 & 2)

## 1. Vue d'Ensemble & Démarche Itérative

Le module `PkgTableauBord` s'appuie sur un développement **itératif par sprint**.
Pour les **Sprints 1 & 2** :
- Le module enrichit les tables existantes de `PkgRealisationProjets` via des colonnes de cache horodatées et dénormalisées.
- Les futures tables dédiées (logs d'activité formateur, remédiations, bordereaux) seront intégrées dans leurs sprints respectifs.

---

## 2. Règle de Nommage Obligatoire (Architecture SoliLMS)

> [!IMPORTANT]
> Tout champ calculable, agrégé ou dénormalisé stocké en base de données pour accélérer les requêtes de pilotage et KPIs **DOIT impérativement être préfixé par `cache_`** (ex: `cache_date_lancement`, `cache_nombre_tentatives_prototype`).

---

## 3. Enrichissements des Tables SoliLMS Existantes

### Sprint 1 : Indicateur 01 — Démarrage du Travail ($T_{\text{amorçage}}$)
- **Table `affectation_projets`** :
  - `cache_date_lancement` : DateTime NULL (Lancement officiel du sprint par le formateur pour la cohorte, indexé).
- **Table `realisation_projets`** :
  - `cache_date_premiere_soumission` : DateTime NULL (Première tâche soumise vers `TO_APPROVE` / `READY_FOR_LIVE_CODING`, indexé).
  - `cache_date_lancement` : DateTime NULL (Dénormalisation de la date de lancement de l'affectation, indexé).

### Sprint 2 : Indicateur 02 — Validation du Prototype ($R_{\text{gating}}$)
- **Table `realisation_projets`** :
  - `cache_nombre_tentatives_prototype` : UnsignedSmallInteger DEFAULT 0 (Nombre total d'évaluations/soutenances de prototype subies par l'apprenant sur le projet).
  - `cache_est_valide_prototype_premier_essai` : Boolean NULL DEFAULT NULL (Indicateur binaire figé lors du premier essai : `true` si validé/`APPROVED` dès la tentative 1, `false` si ajourné/remédiation à la tentative 1, `NULL` si pas encore évalué, indexé).
  - `cache_date_validation_prototype` : DateTime NULL (Date et heure de validation effective finale du prototype).

---

## 4. Gouvernance Gapp & `$fillable` (Règle Inviolable)

- **Interdiction de Surcharge Manuelle** : Ne **JAMAIS** surcharger la propriété `$fillable` ou appeler `$this->mergeFillable()` dans le constructeur des modèles enfants Eloquent (`RealisationProjet`, `AffectationProjet`).
- **Synchronisation Automatique** : Lors de l'ajout de nouvelles colonnes en base :
  1. Le développeur applique la migration (`php artisan migrate`).
  2. Métadonnées synchronisées via `gapp meta:sync`.
  3. Classes de base régénérées via `gapp make:crud RealisationProjet`.

---

## 5. Commandes Artisan de Rattrapage & Maintenance

- **Sprint 1 ($T_{\text{amorçage}}$)** :
  ```bash
  php artisan dashboard:recalculer-amorcage
  ```
  Fiche technique : [docs/commande-artisan-amorcage.md](file:///c:/AppServer/solicode-lms/docs/commande-artisan-amorcage.md).

- **Sprint 2 ($R_{\text{gating}}$)** :
  ```bash
  php artisan dashboard:recalculer-gating
  ```
  Calcule `cache_nombre_tentatives_prototype` et fige `cache_est_valide_prototype_premier_essai` d'après l'historique d'évaluation des tâches de prototype Niveau 2.
