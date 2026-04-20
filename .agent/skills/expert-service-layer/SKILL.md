---
name: expert-service-layer
description: "Expertise de l'architecture modulaire de la couche Service, des Traits et des règles de modification (gapp)."
---

# Skill : Expert Couche Service (expert-service-layer)

## 🎯 Compétence Principale
Ce skill guide l'agent sur la façon de structurer, analyser et modifier la couche Service métier dans le projet Solicode LMS, en respectant rigoureusement l'architecture générée par `gapp` et l'héritage objet.

## 🏗️ Architecture et Règle d'Héritage
La couche Service repose sur une hiérarchie stricte sur 3 niveaux :
1. **`BaseService` (Niveau Core)** : Classe abstraite fondamentale (`Modules\Core\Services\BaseService`) implémentant le `ServiceInterface`.
2. **`Base[Model]Service` (Niveau Généré)** : Classe intermédiaire générée automatiquement par l'outil `gapp` (ex: `BaseProjetService`). **INTERDICTION STRICTE DE MODIFIER CE FICHIER.**
3. **`[Model]Service` (Niveau Métier)** : Classe de service finale modifiable (ex: `ProjetService`). C'est ici que le code personnalisé (surcharges et nouvelles méthodes) doit être implémenté.

### Découpage en Traits (Composition)
Dans les classes de service finales (`[Model]Service`), le code métier complexe est découpé logiciellement à l'aide de Traits regroupés par catégorie de traitement :
- `[Model]CrudTrait` : Hook sur le cycle de vie CRUD (`beforeCreateRules`, `afterCreateRules`, etc.).
- `[Model]ActionsTrait` : Actions métiers spécifiques (import, export, workflow).
- `[Model]CalculTrait` : Calculs de statistiques, agrégations et enrichissement.
- `[Model]RelationsTrait` : Synchronisation et mise à jour des relations.

## ⚙️ Structure de `Core\BaseService`
Il est primordial de s'appuyer sur la structure de `BaseService` pour réutiliser ses capacités lors du développement d'une fonctionnalité métier.

### 1. Traits globaux intégrés dans `BaseService`
La conception repose massivement sur ces traits génériques qui gèrent le flux d'exécution standard :
- **Traitement de requêtes** : `QueryBuilderTrait`, `FilterTrait`, `SortTrait` (`defaultSort()`), `PaginateTrait`.
- **Méga-fonctions CRUD** : `CrudReadTrait`, `CrudCreateTrait`, `CrudUpdateTrait`, `CrudDeleteTrait`, `CrudEditTrait`.
- **Divers** : `MessageTrait`, `RelationTrait`, `StatsTrait`, `JobTrait`, `HandleThrowableTrait`.

### 2. Propriétés ou Méthodes Clés à maîtriser et/ou à surcharger
- `protected array $index_with_relations` : Pour forcer le chargement Eager (EagerLoading) de certaines relations.
- `public function defaultSort($query)` : Permet de définir le tri par défaut (peut être surchargé dans l'enfant direct pour modifier par exemple le tri par `created_at`).
- `public function getFieldsSearchable(): array` : Méthode abstraite retournant les champs cherchables.
- `public function editableFieldsByRoles(): array` : Méthode définissant les champs modifiables selon le Spatie Role.
- `protected function authorize(string $ability, mixed $entity)` : S'assure que l'action est permise vis-à-vis des gates et policies.
- Variables d'état : `$this->viewState` (pour les filtres et sessions) et `$this->sessionState`.

## ⚡ Actions Atomiques

### Action A : Analyser la couche service
- **Description** : Avant de modifier une fonctionnalité, inspecter sa hiérarchie de Service. On vérifie `[Model]Service` et ses Traits associés.
- **Règle** : Ne pas chercher à comprendre la logique uniquement dans les contrôleurs. Toute la logique centrale réside dans le Service de l'entité et ses Traits. Ne jamais chercher à la placer dans le Base[Model]Service.

### Action B : Étendre et Surcharger les comportements (sans toucher à Gapp)
- **Description** : Implémenter la logique métier dans la classe enfant (`[Model]Service` ou l'un de ses Traits métier).
- **Consignes** :
  - **Surcharge** : Surcharger les hooks issus du cycle de vie (ex: `beforeCreateRules`, `defaultSort`) ou des variables (ex: `index_with_relations`) directement dans le `[Model]Service` ou le `[Model]CrudTrait`.
  - **Nouvelle Logique** : Placer les algorithmes ou la logique unique dans un trait métier approprié tel que `[Model]CalculTrait` ou `[Model]ActionsTrait`.
  - **Générateur (GAPP)** : La classe `Base[Model]Service` est intouchable ; elle est périodiquement regénérée. Toutes les personnalisations doivent exister dans le fichier Service final. Vous devez vérifier la signature des méthodes de parent `BaseService` et `Base[Model]Service` et les exploiter si possible au lieu de les réinventer.
