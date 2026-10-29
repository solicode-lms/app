# Contexte IA du Module : PkgFormation

## 1. Responsabilité Métier
Le package **PkgFormation** constitue le socle du référentiel académique et de la structure pédagogique de Solicode LMS.
Il remplit deux missions clés :
1. **Référentiel des structures de formation :** Définition des filières (`Filiere`), des modules d'enseignement (`Module`), des années scolaires/de formation (`AnneeFormation`) et des spécialités techniques (`Specialite`).
2. **Gestion du corps enseignant :** Gestion des profils de formateurs (`Formateur`), création synchronisée des comptes utilisateurs (`User`) avec le rôle `formateur`, et affectation croisée aux groupes d'apprenants et aux spécialités.

---

## 2. Modèles & Relations Principales

### `Formateur` (Table : `formateurs`)
- **Hérite de :** `BaseFormateur`
- **Champs clés :** `matricule`, `nom`, `prenom`, `email`, `tele_num`, `adresse`, `diplome`, `echelle`, `echelon`, `user_id`, `reference`.
- **Relations directes :**
  - `user` : `belongsTo(User::class, 'user_id')` — Compte utilisateur Spatie associé.
  - `specialites` : `belongsToMany(Specialite::class, 'formateur_specialite')` — Spécialités enseignées.
  - `groupes` : `belongsToMany(Groupe::class, 'formateur_groupe')` — Groupes d'apprenants encadrés.
- **Relations inter-modules :**
  - `evaluateur` : `hasOne(Evaluateur::class)` *(PkgEvaluateurs)*.
  - `chapitres` : `hasMany(Chapitre::class, 'formateur_id')` *(PkgCompetences)*.
  - `projets` : `hasMany(Projet::class, 'formateur_id')` *(PkgCreationProjet)*.
  - `commentaireRealisationTaches` : `hasMany(CommentaireRealisationTache::class)` *(PkgRealisationTache)*.
  - `etatRealisationTaches` : `hasMany(EtatRealisationTache::class)` *(PkgRealisationTache)*.
  - `logActiviteFormateurs` : `hasMany(LogActiviteFormateur::class)` *(PkgTableauBord)*.

### `Filiere` (Table : `filieres`)
- **Hérite de :** `BaseFiliere`
- **Champs clés :** `code`, `nom`, `description`, `reference`.
- **Relations :**
  - `modules` : `hasMany(Module::class, 'filiere_id')` — Modules constitutifs du cursus.
  - `groupes` : `hasMany(Groupe::class, 'filiere_id')` *(PkgApprenants)*.
  - `projets` : `hasMany(Projet::class, 'filiere_id')` *(PkgCreationProjet)*.
  - `sessions` : `hasMany(SessionFormation::class, 'filiere_id')` *(PkgSessions)*.

### `Module` (Table : `modules`)
- **Hérite de :** `BaseModule`
- **Champs clés :** `code`, `nom`, `description`, `masse_horaire`, `filiere_id`, `isHaveUa`, `reference`.
- **Relations :**
  - `filiere` : `belongsTo(Filiere::class, 'filiere_id')`.
  - `competences` : `hasMany(Competence::class, 'module_id')` *(PkgCompetences)*.
  - `realisationModules` : `hasMany(RealisationModule::class, 'module_id')` *(PkgApprentissage)*.
  - `bordereauxOfficiels` : `hasMany(BordereauxOfficiel::class, 'module_id')` *(PkgTableauBord)*.

### `AnneeFormation` (Table : `annee_formations`)
- **Hérite de :** `BaseAnneeFormation`
- **Champs clés :** `titre`, `date_debut`, `date_fin`, `reference` (ex: `2025-2026`).
- **Relations :**
  - `affectationProjets` : `hasMany(AffectationProjet::class, 'annee_formation_id')` *(PkgRealisationProjets)*.
  - `groupes` : `hasMany(Groupe::class, 'annee_formation_id')` *(PkgApprenants)*.
  - `bordereauxOfficiels` : `hasMany(BordereauxOfficiel::class, 'annee_formation_id')` *(PkgTableauBord)*.

### `Specialite` (Table : `specialites`)
- **Hérite de :** `BaseSpecialite`
- **Champs clés :** `nom`, `description`, `reference`.
- **Relations :**
  - `formateurs` : `belongsToMany(Formateur::class, 'formateur_specialite')`.

---

## 3. Services & Logique Métier

### `FormateurService` (`Modules\PkgFormation\Services\FormateurService`)
- **Relations Eager-Loadées :** `protected array $index_with_relations = ['specialites', 'groupes']`.
- **Surcharges et Méthodes Métier Clés :**
  - `create($data)` : À la création d'un formateur, si aucun `user_id` n'est rattaché, instancie automatiquement un compte `User` via `UserService`, lui assigne le rôle `Role::FORMATEUR_ROLE`, génère une adresse email institutionnelle (`@ofppt-edu.ma`) et un mot de passe sécurisé.
  - `initPassword(int $formateurId)` : Réinitialise le mot de passe du compte utilisateur associé via `UserService::initPassword`.
  - `getApprenants($formateur_id)` : Extrait la liste des apprenants (`Apprenant`) assignés aux groupes pris en charge par le formateur (`whereHas('groupes.formateurs')`).

### `AnneeFormationService` (`Modules\PkgFormation\Services\AnneeFormationService`)
- **Méthodes Métier Clés :**
  - `getOrCreateFromDateInscription(Carbon $date)` : Calcule dynamiquement le millésime scolaire (seuil de rentrée fixé à septembre) et crée l'enregistrement d'année scolaire `YYYY-YYYY+1` avec dates de début (01/09) et de fin (31/08) si absent.
  - `getCurrentAnneeFormation()` : Retourne ou instancie l'année de formation courante basée sur `Carbon::now()`.

### `ModuleService` (`Modules\PkgFormation\Services\ModuleService`)
- **Relations Eager-Loadées :** `$index_with_relations = ['filiere', 'competences']`.
- **Tri par défaut :** `defaultSort($query)` applique systématiquement un tri ascendant sur `reference`.

### `SpecialiteService` (`Modules\PkgFormation\Services\SpecialiteService`)
- **Relations Eager-Loadées :** `$index_with_relations = ['formateurs']`.

### `FiliereService` (`Modules\PkgFormation\Services\FiliereService`)
- CRUD standard basé sur `BaseFiliereService`.

---

## 4. Particularités UI, Filtres & Vues

### Configuration des Filtres Dynamiques (`TableUI / FilterUI`)
- **Dans `FormateurService::initFieldsFilterable()` :**
  - Filtre ManyToMany dynamique par spécialité (`specialite_id`) avec `generateManyToManyFilter()`.
  - Filtre ManyToMany dynamique par groupe (`groupe_id`) avec `generateManyToManyFilter()`.

### Structure des Vues Blade
- **Vues CRUD standard :** Situées dans `resources/views/[model]/` (`_index.blade.php`, `_table.blade.php`, `_fields.blade.php`, `_edit.blade.php`, `_show.blade.php`).
- **Extensions personnalisées :** Le sous-dossier `custom/` de chaque entité contient les extensions passthrough pour surcharges sans rupture du générateur Gapp.

### Permissions & Autorisations Notables
- Permissions de contrôleur spécifiques : `initPassword` pour la gestion des accès formateur.
- Exports/Imports CSV gérés via `App/Exports` et `App/Imports` pour `Formateur`, `Filiere`, `Module`, `AnneeFormation`, `Specialite`.
