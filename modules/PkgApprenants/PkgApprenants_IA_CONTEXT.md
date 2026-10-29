# Contexte IA du Module : PkgApprenants

## 1. Responsabilité Métier
Le package **PkgApprenants** gère l'ensemble du cycle de vie des apprenants au sein de Solicode LMS :
1. **Gestion des Apprenants :** Profils administratifs et académiques (`Apprenant`), intégration avec les comptes utilisateurs (`User`) et le rôle `apprenant`.
2. **Structure des Groupes & Sous-Groupes :** Organisation promotionnelle (`Groupe`, lié à une `Filiere` et une `AnneeFormation`) et regroupements de travail (`SousGroupe`).
3. **Référentiels Auxiliaires :** Gestion des niveaux scolaires d'entrée (`NiveauxScolaire`) et des nationalités (`Nationalite`).
4. **Indicateurs d'Activité & Widgets :** Fourniture de sources de données dynamiques pour les tableaux de bord (apprenants avec/sans tâches en cours, à faire, ou sans tâche terminée depuis 7 jours).

---

## 2. Modèles & Relations Principales

### `Apprenant` (Table : `apprenants`)
- **Hérite de :** `BaseApprenant`
- **Champs clés :** `matricule`, `nom`, `prenom`, `nom_arab`, `prenom_arab`, `email`, `tele_num`, `date_naissance`, `cne`, `cin`, `actif`, `user_id`, `niveaux_scolaire_id`, `nationalite_id`, `reference`.
- **Relations directes :**
  - `user` : `belongsTo(User::class, 'user_id')` — Compte d'authentification Spatie.
  - `groupes` : `belongsToMany(Groupe::class, 'apprenant_groupe')` — Groupes d'affectation.
  - `niveauxScolaire` : `belongsTo(NiveauxScolaire::class)`.
  - `nationalite` : `belongsTo(Nationalite::class)`.
- **Relations inter-modules :**
  - `realisationTaches` : `hasMany(RealisationTache::class)` *(PkgRealisationTache)*.
  - `affectationProjets` : `hasMany(AffectationProjet::class)` *(PkgRealisationProjets)*.
  - `realisationCompetences` : *(PkgCompetences)*.
- **Scope Global Actif :** Le modèle intègre un Global Scope `inactif` qui filtre par défaut sur `actif = true` sauf si le filtre explicite `actif` est activé dans le `ViewStateService`.

### `Groupe` (Table : `groupes`)
- **Hérite de :** `BaseGroupe`
- **Champs clés :** `code`, `nom`, `description`, `filiere_id`, `annee_formation_id`, `reference`.
- **Relations :**
  - `filiere` : `belongsTo(Filiere::class, 'filiere_id')` *(PkgFormation)*.
  - `anneeFormation` : `belongsTo(AnneeFormation::class, 'annee_formation_id')` *(PkgFormation)*.
  - `apprenants` : `belongsToMany(Apprenant::class, 'apprenant_groupe')`.
  - `formateurs` : `belongsToMany(Formateur::class, 'formateur_groupe')` *(PkgFormation)*.
  - `sousGroupes` : `hasMany(SousGroupe::class)`.

### `SousGroupe` (Table : `sous_groupes`)
- **Hérite de :** `BaseSousGroupe`
- **Champs clés :** `nom`, `groupe_id`, `reference`.
- **Relations :**
  - `groupe` : `belongsTo(Groupe::class)`.
  - `apprenants` : `belongsToMany(Apprenant::class, 'apprenant_sous_groupe')`.

---

## 3. Services & Logique Métier

### `ApprenantService` (`Modules\PkgApprenants\Services\ApprenantService`)
- **Relations Eager-Loadées :** `protected array $index_with_relations = ['groupes']`.
- **Traits métier associés :** `ApprenantServiceWidgets` (calculs d'indicateurs de tableau de bord).
- **Sources de données configurées (`$dataSources`) :**
  - `apprenantSansTacheEnCours` : Apprenants sans tâches actives.
  - `apprenantAvecTacheEnCours` : Apprenants en cours de travail.
  - `apprenantAvecTacheAFaire` : Apprenants avec tâches planifiées.
  - `apprenantSansTacheAFaire` : Apprenants en risque de sous-charge.
  - `apprenantSansTacheTerminee7Jours` : Alerte apprenants sans validation depuis 7 jours.
- **Surcharges CRUD :**
  - `create($data)` : Création synchronisée du compte `User` avec le rôle `Role::APPRENANT_ROLE` et mot de passe chiffré par défaut.
  - `find($id)` : Neutralisation du Global Scope `inactif` via `withoutGlobalScope('inactif')` pour permettre l'accès d'administration aux apprenants inactifs.

### `GroupeService` (`Modules\PkgApprenants\Services\GroupeService`)
- Gestion des affectations promotionnelles et synchronisation des apprenants par lot.

---

## 4. Particularités UI, Filtres & Vues

### Configuration des Filtres Dynamiques (`TableUI / FilterUI`)
- **Dans `BaseApprenantService::initFieldsFilterable()` :**
  - Filtre ManyToMany par groupes (`groupe_id`) avec détection automatique du contexte `ViewState`.
  - Filtres ManyToOne par Filière (`filiere_id`), Année de Formation (`annee_formation_id`), Niveau Scolaire et Nationalité.
  - Filtre booléen d'état actif/inactif (`actif`).

### Vues Blade
- Vues modulaires standard sous `resources/views/apprenant/` et `resources/views/groupe/`.
- Sous-dossiers `custom/` réservés aux extensions spécifiques sans altération des templates générés par Gapp.
