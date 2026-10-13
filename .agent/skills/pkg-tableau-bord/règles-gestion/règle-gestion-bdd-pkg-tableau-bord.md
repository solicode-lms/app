# Règle de Gestion : Modèle de Données PkgTableauBord (Sprint 1)

## 1. Vue d'Ensemble & Démarche Itérative

Le module `PkgTableauBord` s'appuie sur un développement **itératif par sprint**.
Pour le **Sprint 1 (Indicateur 01 — $T_{\text{amorçage}}$)** :
- Aucune nouvelle table dédiée n'est créée dans la base.
- Le module exploite et enrichit les tables existantes de `PkgRealisationProjets` via des colonnes d'horodatage dénormalisées.
- Les futures tables dédiées (logs d'activité, remédiations, bordereaux officiels) seront intégrées dans cette règle de gestion lors de leurs sprints respectifs.

---

## 2. Règle de Nommage Obligatoire (Architecture SoliLMS)

> [!IMPORTANT]
> Tout champ calculable, agrégé ou dénormalisé stocké en base de données pour accélérer les requêtes de pilotage et KPIs **DOIT impérativement être préfixé par `cache_`** (ex: `cache_date_lancement`, `cache_date_premiere_soumission`).

---

## 3. Enrichissements des Tables SoliLMS Existantes (Sprint 1)

Pour permettre des calculs d'amorçage instantanés sans jointures lourdes ni requêtes d'historique lors de l'affichage :

### Table `affectation_projets`
- `cache_date_lancement` : DateTime NULL (Date et heure de lancement officiel du sprint par le formateur pour la cohorte, indexé).

### Table `realisation_projets`
- `cache_date_premiere_soumission` : DateTime NULL (Horodatage de la première tâche soumise par l'apprenant vers un état de validation comme `TO_APPROVE` ou `READY_FOR_LIVE_CODING`, indexé).
- `cache_date_lancement` : DateTime NULL (Dénormalisation de la date de lancement de l'affectation de projet associée pour requêtes KPI directes sans jointure, indexé).

---

## 4. Gouvernance Gapp & `$fillable` (Règle Inviolable)

- **Interdiction de Surcharge Manuelle** : Ne **JAMAIS** surcharger la propriété `$fillable` ou appeler `$this->mergeFillable()` dans le constructeur des modèles enfants Eloquent (`RealisationProjet`, `AffectationProjet`).
- **Synchronisation Automatique** : Lors de l'ajout de nouvelles colonnes en base :
  1. Le développeur applique la migration (`php artisan migrate`).
  2. Métadonnées synchronisées via `gapp meta:sync`.
  3. Classes de base régénérées via `gapp make:crud RealisationProjet` et `gapp make:crud AffectationProjet`.

---

## 5. Commande Artisan de Rattrapage & Maintenance

Pour initialiser ou synchroniser les colonnes `cache_` sur les données existantes de la base :
```bash
php artisan dashboard:recalculer-amorcage
```
- **Rôle** : Calcule `cache_date_lancement` pour chaque affectation, synchronise chaque réalisation de projet liée, et reconstitue `cache_date_premiere_soumission` à partir de `historique_realisation_taches`.
- **Garantie d'Idempotence** : Ne réécrit jamais une date de première soumission déjà existante.
- **Documentation** : Fiche technique disponible dans [docs/commande-artisan-amorcage.md](file:///c:/AppServer/solicode-lms/docs/commande-artisan-amorcage.md).
