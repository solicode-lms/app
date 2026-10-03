# Capacité : Configuration des Filtres Personnalisés (initFieldsFilterable)

## 🎯 Rôle
Permet de définir, de configurer ou de restreindre dynamiquement les filtres disponibles sur la page de liste (index) d'un modèle.

## ⚙️ Mécanisme Standard
Chaque service généré contient une méthode `initFieldsFilterable()` (héritée ou générée dans `Base[Model]Service`) qui remplit le tableau `$this->fieldsFilterable`.

## 🛠️ Comment ajouter ou modifier des filtres

Pour personnaliser les filtres, surchargez `initFieldsFilterable()` dans la classe de service finale `[Model]Service` (ou son trait associé tel que `[Model]GetterTrait`).

### Signature de la méthode
```php
public function initFieldsFilterable()
{
    // 1. Charger les variables de portée (Scope variables) définies par Gapp
    $scopeVariables = $this->viewState->getScopeVariables('nomModelMiniscule');
    
    // 2. Réinitialiser le tableau des filtres
    $this->fieldsFilterable = [];

    // 3. Ajouter les filtres souhaités si la variable n'est pas déjà dans le scope
    if (!array_key_exists('relation_id', $scopeVariables)) {
        $this->fieldsFilterable[] = $this->generateManyToOneFilter(
            __("PkgModule::relation.plural"), 
            'relation_id', 
            Relation::class, 
            'champ_affichage'
        );
    }
}
```

### Helpers disponibles (définis dans `FilterTrait`)
- `generateManyToOneFilter(string $label, string $field, string $model, string $display_field, $data = null, $targetDynamicDropdown = null, ...)`
- `generateManyToManyFilter(string $label, string $field, string $relatedModel, string $display_field, $data = null, ...)`
- `generateRelationFilter(string $label, string $relation, string $relatedModel, string $displayField = 'id', string $valueField = 'id', $data = null, ...)`

### Filtres Imbriqués / Dépendants (Dynamic Dropdowns)
Pour qu'un filtre recharge les options d'un autre filtre de manière dynamique (ex: choisir un Groupe recharge uniquement les Apprenants de ce groupe) :
```php
$this->fieldsFilterable[] = $this->generateRelationFilter(
    __("PkgApprenants::Groupe.plural"), 
    'RealisationProjet.AffectationProjet.Groupe_id', 
    Groupe::class, 
    "code",
    "id",
    $groupes,
    "[name='RealisationProjet.Affectation_projet_id']", // Sélecteur CSS cible à rafraîchir
    route('affectationProjets.getDataHasEvaluateurs'),  // URL API pour charger les données filtrées
    "groupe_id"                                         // Paramètre de filtre
);
```

### ⚠️ Règle Critique ViewState (Ordre d'Appel Obligatoire)
Avant d'appeler `loadLastFilterIfEmpty()`, vous devez définir le contexte sur le `ViewState` pour éviter que le filtre soit chargé sous la clé `"default_context"`.
```php
// ✅ Pattern correct
$this->viewState->setContextKeyIfEmpty('monModel.index');
$this->monService->loadLastFilterIfEmpty();
$filterVariables = $this->viewState->getFilterVariables('monModel');
```

### Redéfinition complète des filtres (Exemple: ProjetService)
Si vous souhaitez modifier le comportement d'un filtre par défaut (ex: rendre `filiere_id` dynamique), la meilleure approche consiste à ne pas appeler `parent::initFieldsFilterable()` et à redéfinir explicitement tous les filtres souhaités en utilisant `generateRelationFilter`.

**Exemple d'application (`ProjetService.php`) :**
```php
    public function initFieldsFilterable()
    {
        $scopeVariables = $this->viewState->getScopeVariables('projet');
        $this->fieldsFilterable = [];

        // 1. Filtre dynamique : Filière -> Session de formation
        if (!array_key_exists('filiere_id', $scopeVariables)) {
            $filiereService = new \Modules\PkgFormation\Services\FiliereService();
            $filiereIds = $this->getAvailableFilterValues('filiere_id');
            $filieres = $filiereService->getByIds($filiereIds);

            // Afficher le filtre seulement si l'utilisateur est admin ou s'il y a plus d'une filière
            if ($filieres->count() > 1 || \Illuminate\Support\Facades\Auth::user()->hasRole('admin')) {
                $this->fieldsFilterable[] = $this->generateRelationFilter(
                    __("PkgFormation::filiere.plural"),
                    'filiere_id',
                    \Modules\PkgFormation\Models\Filiere::class,
                    'code',
                    'id',
                    $filieres,
                    "[name='session_formation_id'],[name='affectationProjets.groupe_id']", // Cibles
                    route('sessionFormations.getData') . "," . route('groupes.getData'),   // URLs API
                    "filiere_id,filiere_id"                                                // Paramètres
                );
            }
        }

        // 2. Redéfinir les autres filtres normalement...
        if (!array_key_exists('session_formation_id', $scopeVariables)) {
            $sessionFormationService = new \Modules\PkgSessions\Services\SessionFormationService();
            $sessionFormationIds = $this->getAvailableFilterValues('session_formation_id');
            $sessionFormations = $sessionFormationService->getByIds($sessionFormationIds);

            $this->fieldsFilterable[] = $this->generateRelationFilter(
                __("PkgSessions::sessionFormation.plural"), 
                'session_formation_id', 
                \Modules\PkgSessions\Models\SessionFormation::class, 
                'code',
                'id',
                $sessionFormations
            );
        }
    }
```
