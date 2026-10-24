# Mécanique Backend CRUD : Solicode LMS

> **PRIORITÉ 1 (Contexte Local)**  
> Règles opérationnelles pour la couche Service et Contrôleur de Solicode LMS.

## 1. Personnalisation des Filtres (`initFieldsFilterable`)
Pour ajouter ou modifier des filtres sur un listing :
- Surcharger la méthode `initFieldsFilterable()` dans la classe enfant `[Model]Service.php` (ou son trait `[Model]GetterTrait.php`).
- Utiliser les helpers de filtrage :
```php
// Filtre ManyToOne (ex: par Filière)
$this->fieldsFilterable[] = $this->generateManyToOneFilter(
    __("PkgFormation::filiere.plural"),
    'filiere_id',
    \Modules\PkgFormation\Models\Filiere::class,
    'nom',
    $filieres
);

// Filtre Relation avec rechargement AJAX dépendant
$this->fieldsFilterable[] = $this->generateRelationFilter(
    __("PkgApprenants::Groupe.plural"),
    'RealisationProjet.AffectationProjet.Groupe_id',
    Groupe::class,
    "code",
    "id",
    $groupes,
    "[name='RealisationProjet.Affectation_projet_id']",
    route('affectationProjets.getDataHasEvaluateurs'),
    "groupe_id"
);
```

## 2. Calculs Dynamiques dans les Formulaires (`dataCalcul`)
Lorsqu'un champ possède l'attribut `data-calcule` dans la vue :
- Implémenter la méthode `dataCalcul($data)` dans `[Model]Service.php`.
- Effectuer les calculs sans persistance en base et retourner l'entité hydratée :
```php
public function dataCalcul($data)
{
    $instance = !empty($data['id']) ? $this->find($data['id']) : $this->createInstance($data);
    $instance->fill($data);
    
    // Calculs métier
    $instance->total = ($instance->valeur1 ?? 0) + ($instance->valeur2 ?? 0);
    
    return $instance;
}
```

## 3. Hooks de Cycle de Vie CRUD
Pour injecter une règle métier ou valider des contraintes avant persistance :
- Utiliser les hooks dans le service ou son trait `CrudTrait` :
  - `beforeCreateRules(&$data)`
  - `afterCreateRules($item, $data)`
  - `beforeUpdateRules(&$data, $id)`
  - `afterUpdateRules($item, $data)`
  - `beforeDeleteRules($id)`
- En cas de validation non franchie, lever une `BlException` :
```php
use Modules\Core\App\Exceptions\BlException;

if ($data['note'] < 0 || $data['note'] > 20) {
    throw new BlException("La note saisie doit être comprise entre 0 et 20.");
}
```

## 4. Ordre Obligatoire du ViewState & Filtres Sauvegardés
Le `ViewStateService` organise son état par `contextKey`. Les filtres persistés en base de données sont également indexés par `contextKey`.

### ⚠️ Ordre d'Appel OBLIGATOIRE
**AVANT tout appel à `loadLastFilterIfEmpty()`, il est OBLIGATOIRE d'appeler `setContextKeyIfEmpty()` sur le `ViewState`.**

Sans cela, le système opère sous la clé `"default_context"` et ne retrouve **pas** les filtres sauvegardés :
```php
// 1. Définir le contexte
$this->viewState->setContextKeyIfEmpty('monModel.index');

// 2. Charger les filtres depuis la base
$this->monService->loadLastFilterIfEmpty();

// 3. Lire les variables de filtre
$filterVariables = $this->viewState->getFilterVariables('monModel');
```
Cette règle s'applique partout où les filtres sont nécessaires hors du flux standard `index()` (exports CSV/Excel, rapports).
