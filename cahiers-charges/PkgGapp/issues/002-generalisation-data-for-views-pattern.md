# Issue : Refactoring des méthodes de rendu (edit, create, show) pour faciliter l'override via le pattern "Template Method"

## Contexte
Actuellement, les contrôleurs générés par Gapp (ex: `BaseAffectationProjetController`) effectuent la collecte des données ET le rendu de la vue dans la même méthode (`edit()`, `show()`, `create()`).

## Problème
Lorsqu'un contrôleur enfant (ex: `AffectationProjetController`) souhaite injecter une nouvelle variable dans la vue, modifier un filtre, ou éviter de charger certaines données, il est impossible de surcharger proprement la méthode.
L'appel à `parent::edit($id)` retourne directement une réponse HTTP (View ou HTML string). Le développeur est donc contraint de copier-coller l'intégralité de la méthode parent dans la classe enfant, ce qui :
- Duplique le code (Non-DRY).
- Fait perdre l'héritage des vues complexes (ex: le fait de retourner `_edit` vs `_fields`).
- Casse la maintenabilité (les mises à jour futures de Gapp sur la méthode de base ne seront pas répercutées sur la classe enfant).

## Solution Proposée (Généralisation Gapp)
Implémenter le pattern **Template Method** dans les générateurs de contrôleurs de Gapp.
Pour chaque action qui retourne une vue (`edit`, `create`, `show`), séparer la logique en deux méthodes distinctes :
1. `dataFor[Action]View($id)` : Responsable uniquement de préparer et retourner le tableau associatif des données.
2. `[action]($id)` : Responsable d'appeler `dataFor...` et de retourner la vue (ou JSON si AJAX).

### Exemple d'implémentation attendue dans le template Gapp (BaseController) :

**Pour la méthode `edit` :**
```php
    /**
     * Prépare les données pour la vue d'édition.
     * @return array
     */
    protected function dataForEditView(string $id): array {
        $this->viewState->setContextKey('nomDuModele.edit_' . $id);
        
        // 1. Chargement de l'entité et Autorisation
        $item = $this->monService->edit($id);
        $this->authorize('edit', $item);

        // 2. Chargement des Selects et Relations HasMany
        // ... (Code généré par Gapp) ...
        
        $bulkEdit = false;
        
        // 3. Retourne le tableau de variables consolidées
        return array_merge(
            compact('bulkEdit', 'item', /* autres vars */),
            $hasMany_compact_value // etc...
        );
    }

    /**
     * Action Web : Affiche le formulaire d'édition.
     */
    public function edit(string $id) {
        $viewData = $this->dataForEditView($id);

        if (request()->ajax()) {
            return view('PkgPackage::monModele._edit', $viewData);
        }

        return view('PkgPackage::monModele.edit', $viewData);
    }
```
*(Le même principe s'applique exactement à `create` et `show`)*

### Bénéfice pour le développeur (dans la classe Enfant) :
```php
    protected function dataForEditView(string $id): array {
        // Récupérer les données de base préparées par Gapp (inclut les relations HasMany !)
        $viewData = parent::dataForEditView($id);
        
        // Injecter ou surcharger dynamiquement
        $viewData['ma_nouvelle_variable_metier'] = 'Valeur';
        
        return $viewData;
    }
```

## Fichiers testés manuellement (Workaround)
Avant d'intégrer cette logique dans Gapp, nous avons testé et validé cette approche sur le contrôleur `AffectationProjetController` pour les méthodes `edit`, `create` et `show` :

1. **`modules/PkgRealisationProjets/Controllers/Base/BaseAffectationProjetController.php`**
   - Création de `dataForEditView(string $id): array`, `dataForCreateView(): array`, et `dataForShowView(string $id): array` et déplacement de la logique de préparation.
   - Simplification de `edit(string $id)`, `create()`, et `show(string $id)`.

2. **`modules/PkgRealisationProjets/Controllers/AffectationProjetController.php`**
   - Surcharge de `dataForEditView(string $id)` et `dataForCreateView()` pour valider que l'injection et le filtrage personnalisé fonctionnent proprement sans dupliquer le code de base ni perdre le chargement des `HasMany`.

## Action requise post-généralisation :
- Mettre à jour les templates Gapp (`Controller.ejs` ou similaires) pour intégrer ces sous-méthodes `dataForEditView`, `dataForCreateView`, et `dataForShowView` par défaut pour toutes les entités.
