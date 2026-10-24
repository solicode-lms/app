# Référence : Architecture 3-Tiers & Couche Service
 
## 1. Vue d'Ensemble
L'application repose sur un découpage strict à 3 niveaux :
1. **Couche Présentation (HTTP & Vues) :**
   - Contrôleurs dérivant de `Modules\Core\Controllers\Base\AdminController` (ou `PublicController`).
   - Requêtes de validation `FormRequest`.
   - Vues Blade dans `modules/Pkg.../resources/views/`.
2. **Couche Métier (Business Logic Layer - BLL) :**
   - `Modules\Core\Services\BaseService` (Niveau Core universel).
   - `Base[Model]Service` (Niveau généré par Gapp, **protégé et intouchable**).
   - `[Model]Service` (Niveau métier modifiable par le développeur).
   - Traits spécialisés dans `modules/Pkg.../Services/Traits/[Model]/`.
3. **Couche Persistance (Data Access Layer - DAL) :**
   - `Base[Model]` (Modèle Eloquent généré par Gapp, **protégé**).
   - `[Model]` (Modèle Eloquent enfant modifiable).

## 2. Règle d'Héritage et Template Method dans les Contrôleurs
Ne jamais copier-coller entièrement les méthodes de rendu du contrôleur de base.
Surcharger uniquement les méthodes de préparation de données :
```php
protected function dataForEditView(string $id): array {
    $viewData = parent::dataForEditView($id);
    $viewData['mes_donnees_custom'] = 'valeur';
    return $viewData;
}
```

## 3. Gestion des Erreurs Métier (BlException)
Dans la couche Service, les blocages métiers doivent obligatoirement lever une `BlException` :
```php
use Modules\Core\App\Exceptions\BlException;

if ($conditionEchec) {
    throw new BlException("L'opération est refusée : la date limite est dépassée.");
}
```
Cette exception est interceptée automatiquement par le Handler global et affichée dans l'interface utilisateur sans crash de page.

---

## 4. Standardisation des Traits de Service (Seuil Critique 500 Lignes)
Si une classe de Service dépasse **500 lignes**, son code doit obligatoirement être découpé en Traits spécialisés situés dans `modules/Pkg.../Services/Traits/{NomEntite}/` selon les 8 catégories exclusives suivantes :

1. **`{Entity}CrudTrait`** : Cycle de vie de base (`create`, `update`, `destroy`) et implémentation des hooks (`beforeCreateRules`, `afterCreateRules`, etc.).
2. **`{Entity}JobTrait`** : Gestion des Jobs asynchrones et listeners d'événements (ex: `ObserverJob`).
3. **`{Entity}ActionsTrait`** : Actions métier spécifiques ("Verbes" du domaine) hors CRUD classique (ex: `import`, `export`, `valider`, `cloturer`).
4. **`{Entity}GetterTrait`** : Scopes, filtres complexes, requêtes de lecture et sélecteurs de données (`getValidatedItems`, `filterByContext`).
5. **`{Entity}DataCalculTrait`** : Implémentation de la méthode `dataCalcul` pour enrichir les formulaires (calculs auto, valeurs contextuelles par défaut).
6. **`{Entity}RelationsTrait`** : Gestion des relations complexes, synchronisations inter-services (ex: `syncTacheRealisation`), collections filles.
7. **`{Entity}StateTrait`** : (Optionnel) Machine à états, validations d'étapes de workflow et transitions de statuts.
8. **`{Entity}MassCrudTrait`** : Opérations par lot, initialisation en masse ou réaffectation en volume.

---

## 5. Ordre Strict d'Importation des Traits
Dans la classe de Service principale, les Traits doivent être importés via `use` dans cet **ordre strict** pour garantir la bonne surcharge et l'interception des hooks :
```php
class TacheService extends BaseTacheService
{
    use TacheCrudTrait;      // 1. Base du cycle de vie et hooks
    use TacheJobTrait;       // 2. Écoute et intercepte le CRUD
    use TacheActionsTrait;   // 3. Logique métier spécifique
    use TacheGetterTrait;    // 4. Lecture seule et scopes
    use TacheMassCrudTrait;  // 5. Opérations de masse
    
    // ...
}
```

---

## 6. SRP & Encapsulation des Requêtes (Inter-Services)
- **Règle No Direct Model** : Un Service ne doit **JAMAIS** manipuler directement le Model Eloquent d'une autre entité (ex: interdiction formelle de `Projet::where(...)` dans `TacheService`). Il DOIT obligatoirement injecter ou résoudre le Service dédié (`ProjetService`).
- **Encapsulation des Queries** : Les consommateurs externes d'un Service ne doivent **JAMAIS** appeler `allQuery()` ni chaîner de clauses `where` sur un service injecté. Ils doivent obligatoirement appeler une **méthode métier explicite** exposée par ce Service (ex: `$projetService->getActiveByFiliere($filiereId)`).
  > `allQuery()` est réservé à un usage strictement interne à la classe du Service et à ses propres Traits.

---

## 7. Résolution des Dépendances dans les Traits
Pour éviter la surcharge des constructeurs et les dépendances circulaires entre modules :
- **Ne pas injecter** tous les services secondaires dans le `__construct` du Service principal.
- Au sein des **Traits**, instancier les services tiers à la demande via :
```php
$autreService = app(AutreEntiteService::class);
```

---

## 8. Documentation des Scénarios Métier (Mermaid)
- **Scénarios Dédiés** : Tout workflow métier complexe doit être documenté dans :
  `docs/1.scenarios/{Module}/{Entity}/{NomScenario}.scenario.mmd`
- **PHPDoc de Référence** : La classe principale du Service doit référencer ses traits et ses scénarios :
```php
/**
 * Service de gestion des Projets
 *
 * @uses ProjetCrudTrait Cycle de vie et hooks
 * @uses ProjetActionsTrait Workflow de validation
 * @see docs/1.scenarios/PkgCreationProjet/Projet/ValidationProjet.scenario.mmd
 */
```
