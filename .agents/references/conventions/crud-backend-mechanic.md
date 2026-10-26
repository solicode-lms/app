# Mécanique Backend CRUD : Solicode LMS

> **PRIORITÉ 1 (Contexte Local)**  
> Guides d'implémentation opérationnelle pour la couche Service, les Traits et le Contrôleur.
> Les contraintes invariables sont régies par [.agents/rules/critical-rules.md](file:///c:/AppServer/solicode-lms/.agents/rules/critical-rules.md).

## 1. Personnalisation des Filtres (`initFieldsFilterable`)
Pour ajouter ou modifier des filtres sur un listing :
- Surcharger la méthode `initFieldsFilterable()` dans la classe enfant `[Model]Service.php` (ou son trait `[Model]FilterTrait.php`).
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
- Utiliser les hooks dans le service ou ses traits spécialisés (`CrudTrait`, `CreationHookTrait`) :
  - `beforeCreateRules(&$data)`
  - `afterCreateRules($item)`
  - `beforeUpdateRules(&$data, $id)`
  - `afterUpdateRules($item)`
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

## 5. Organisation des Traits & Services Métier

### Structuration des Traits de Service
Conformément à la règle de taille des fichiers (< 300 lignes après refactoring, voir [critical-rules.md](file:///c:/AppServer/solicode-lms/.agents/rules/critical-rules.md)) :
- Regrouper les méthodes par domaine de responsabilité dans `modules/Pkg.../Services/Traits/[NomModel]/` :
  - `[Model]CrudTrait.php` : Hooks de mise à jour (`beforeUpdateRules`, `afterUpdateRules`).
  - `[Model]CreationHookTrait.php` : Hooks de création (`beforeCreateRules`, `afterCreateRules`).
  - `[Model]GetterTrait.php` : Requêtes, scopes, tris et préparation des données de vue.
  - `[Model]FilterTrait.php` : Configuration de la barre de filtres (`initFieldsFilterable`).
  - `[Model]ActionsTrait.php` : Actions métier spécifiques et boutons d'action.
- Importer les traits dans `[Model]Service.php` via des clauses `use`.

### Pattern d'Émergence de Services Métier Dédiés
- Lorsqu'un concept fonctionnel transversal émerge (ex: `Seance`, `SituationPedagogique`, `InactivitePedagogique`), créer un Service autonome dans `modules/Pkg.../Services/` :
```php
namespace Modules\PkgRealisationTache\Services;

class SituationPedagogiqueService
{
    // Constantes, règles de calcul et mapping d'états
}
```
- Consommer ce service via injection de dépendances ou instanciation de conteneur :
```php
$service = app(SituationPedagogiqueService::class);
```
