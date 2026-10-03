# Capacité : Manipulation du ViewState

## 1. Principes de Base
Le `ViewStateService` gère l'état des vues CRUD dans Gapp (Solicode LMS). Il permet de filtrer dynamiquement les données, de restreindre les résultats via des jointures imbriquées, et de gérer l'état de l'interface.

Les variables du ViewState se séparent en 4 grandes familles :
- **filter.** : Utilisé par la barre de recherche standard (ex: `filter.module.code`). Appliqué automatiquement par `allQuery()`.
- **where.** : Applique strictement des conditions AND. Géré nativement par `allQuery()`.
- **orWhere.** : Applique strictement des conditions OR. Géré nativement par `allQuery()`.
- **scope.** : Utilisé pour "verrouiller" le contexte de données (très fréquent pour limiter les options AJAX dans Select2). Géré de manière spécifique via le Global Scope `DynamicContextScope`.

## 2. Le Scope Dynamique (DynamicContextScope)
- Les `scopeVariables` (ex: `scope.module.id`) **NE SONT PAS** traitées nativement par un simple appel à `allQuery()` ni `getData()` sans contexte.
- Elles nécessitent obligatoirement que la requête soit enveloppée dans `withScope()` pour activer le `DynamicContextScope` qui injectera ces filtres :
```php
$this->model::withScope(function () use (...) {
    $query = $this->allQuery();
    // Les scopeVariables sont ajoutées ici via le Scope global
    return $query->get();
});
```
*Note : `PaginateTrait` (pour les datatables) et `getData()` (depuis le patch) utilisent cette approche.*

## 3. Les Chemins Relationnels (Dot Syntax)
Dans Solicode LMS, la fonction `QueryBuilderTrait::applyCondition()` permet de filtrer sur des relations profondes en utilisant des points (`.`).
**Exemple** : 
`$this->viewState->set('scope.module.competences.microCompetences.uniteApprentissages.mobilisationUas.projet.formateur_id', $id);`

- Cela se traduit automatiquement côté serveur par une suite de `whereHas(...)` imbriqués correspondants aux relations (ex: `Module -> Competence -> MicroCompetence ...`).
- **Avantage** : Cela évite d'écrire des jointures ou des requêtes complexes manuellement, et délègue tout le travail de filtrage aux méthodes internes du framework.

## 4. OÙ définir ces ViewState ?
- **Initialisation (Index)** : Pour les vues de base, il faut les configurer dans la méthode `index` du Controller ou `prepareDataForIndexView`.
- **Filtres Avancés (AJAX)** : Pour les listes dynamiques (Select2), on configure généralement les dépendances (scope variables) dans la méthode `initFieldsFilterable` des Services ou directement dans la définition Gapp, afin que le front-end capture la valeur et l'injecte dans les requêtes `getData`.
