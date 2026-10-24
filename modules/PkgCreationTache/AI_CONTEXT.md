# Contexte IA du Module : PkgCreationTache

## 1. Responsabilité Métier
Le package **PkgCreationTache** orchestre la modélisation et la planification des tâches liées aux projets pédagogiques :
1. **Définition des Tâches :** Spécification des activités à réaliser (`Tache`), rattachées à un projet (`Projet`), avec durée estimée, livrables attendus et ordre d'exécution.
2. **Phase et Priorité :** Structuration des étapes du projet (`PhaseProjet`) et qualification de l'importance (`PrioriteTache`).
3. **Alignement Pédagogique :** Identification des tâches constituant des épreuves de prototype (`isPrototype()`).

---

## 2. Modèles & Relations Principales

### `Tache` (Table : `taches`)
- **Hérite de :** `BaseTache`
- **Champs clés :** `titre`, `description`, `date_debut`, `date_fin`, `projet_id`, `priorite_tache_id`, `phase_projet_id`, `ordre`, `reference`.
- **Relations :**
  - `projet` : `belongsTo(Projet::class, 'projet_id')` *(PkgCreationProjet)*.
  - `prioriteTache` : `belongsTo(PrioriteTache::class, 'priorite_tache_id')`.
  - `phaseProjet` : `belongsTo(PhaseProjet::class, 'phase_projet_id')`.
  - `livrables` : `belongsToMany(Livrable::class, 'livrable_tache')` *(PkgCreationProjet)*.
  - `realisationTaches` : `hasMany(RealisationTache::class, 'tache_id')` *(PkgRealisationTache)*.
- **Méthodes Métier Clés :**
  - `generateReference()` : format `{projet->reference}-{titre}`.
  - `isPrototype()` : vérifie si la tâche est rattachée à la phase d'évaluation `N2`.

### `PhaseProjet` (Table : `phase_projets`) & `PrioriteTache` (Table : `priorite_taches`)
- Nomenclature et catégorisation des phases et niveaux de priorité (Basse, Moyenne, Haute, Urgente).

---

## 3. Services & Logique Métier

### `TacheService` (`Modules\PkgCreationTache\Services\TacheService`)
- **Architecture par Traits Spécialisés :**
  - `TacheCrudTrait` : Cycle de vie CRUD, hooks (`beforeCreateRules`, `beforeUpdateRules`).
  - `TacheGetterTrait` : Filtres et requêtes optimisées.
  - `TacheRelationsTrait` : Synchronisation des livrables et compétences associées.
  - `TacheActionsTrait` : Actions métier (génération automatique de tâches depuis les unités d'apprentissage).
- **Relations Eager-Loadées :** `protected array $index_with_relations = ['projet', 'livrables']`.
- **Gestion de l'Ordonnancement :** `$ordreGroupColumn = "projet_id"` (maintien de l'ordre d'affichage au sein du projet).

---

## 4. Particularités UI & Filtres
- **Filtres TableUI :** Filtrage ManyToOne par Projet (`projet_id`), Phase (`phase_projet_id`) et Priorité (`priorite_tache_id`).
- **Drag & Drop Réordonnancement :** Prise en charge du réordonnancement via l'attribut `ordre` groupé par `projet_id`.
