# Capacité : Scope Data In Edit Context (Gapp)

## 1. Objectif
Le métadonnée `scopeDataInEditContext` sert à restreindre (scoper) dynamiquement les données affichées dans un champ `<select>` (relation ManyToOne ou ManyToMany) lors de l'édition (ou création) d'une entité, en fonction d'un autre attribut du contexte courant.

*Exemple pratique : Lors de la création d'une `MobilisationUa`, je veux que la liste déroulante des Unités d'Apprentissage se limite à celles qui appartiennent à la même filière que le `Projet` en cours.*

## 2. Format JSON Gapp

```json
[
  {
    "key": "scope.[chemin_relation_du_select].[attribut_filtre]",
    "value": "[chemin_relation_depuis_entite_courante].[attribut]",
    "modelName": "[Nom_Entite_Courante]"
  }
]
```

### Cas concret (Limiter Unité Apprentissage par Filière du Projet)
Dans `pkg_competences.json` sous la relation `uniteApprentissage` de `MobilisationUa` :
```json
"scopeDataInEditContext": [
  {
    "key": "scope.uniteApprentissage.microCompetence.competence.module.filiere_id",
    "value": "projet.filiere_id",
    "modelName": "MobilisationUa"
  }
]
```

## 3. Mécanisme généré (Ce que fait Gapp)
Lorsqu'il détecte cette configuration, Gapp génère automatiquement ce code dans le `BaseController` (ex: `BaseMobilisationUaController` au sein de la méthode `create()` ou `edit()`) :

```php
// scopeDataInEditContext
$value = $itemMobilisationUa->getNestedValue('projet.filiere_id');
$key = 'scope.uniteApprentissage.microCompetence.competence.module.filiere_id';
$this->viewState->set($key, $value);
```

### Conséquence Frontend
Le système `ViewState` transmet cette clé/valeur au Frontend. Les composants Gapp (comme `Select2`) injecteront alors automatiquement ce paramètre dans leurs requêtes AJAX vers l'API, ce qui limitera les résultats renvoyés par le serveur aux seules entités correspondant à ce filtre.
