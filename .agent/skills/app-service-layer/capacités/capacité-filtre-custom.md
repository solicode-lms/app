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
