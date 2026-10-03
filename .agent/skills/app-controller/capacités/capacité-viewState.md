# Capacité : Utilisation du ViewState et des Scopes Dynamiques

## 1. Principes de Base du `ViewStateService`
Le `ViewStateService` est un mécanisme permettant de conserver et transmettre dynamiquement les variables de contexte (`contextKey`) entre le Frontend (Gapp Page) et le Backend. Il est essentiel pour gérer les requêtes AJAX, les formulaires dépendants et limiter les données accessibles (scopes).

### Règles du ViewState :
- **ContextKey Unique** : Une page `Gapp Page` gère plusieurs `contextKey` (ex: `projet.index`, `projet.edit_1`).
- **Persistance** : Le ViewState n'est pas stocké en session sur le serveur, il est transmis à chaque requête HTTP depuis le navigateur.
- **Familles de variables** :
  - `filter.*` : Filtres standards de recherche.
  - `where.*` / `orWhere.*` : Conditions strictes.
  - `scope.*` : Scopes dynamiques sur les listes et index.
  - `scope_form.*` : Scopes spécifiques aux listes déroulantes dans les formulaires (Select2).

## 2. Le Scope Dynamique (`DynamicContextScope`)
Le fichier `App\Scopes\DynamicContextScope.php` applique automatiquement les filtres définis dans le ViewState sous la forme `scope.*` à toutes les requêtes Eloquent du modèle concerné.

- Si le scope contient des chemins relationnels (dot syntax, ex: `scope.sessionFormation.filiere.groupes.formateurs.id`), le système (`QueryBuilderTrait`) se charge automatiquement de créer les `whereHas` imbriqués correspondants sans avoir à écrire des jointures SQL complexes.

## 3. Application dans les Controllers (`scope_form` et `scope`)
Dans les méthodes `create()` ou `edit()`, vous pouvez initialiser des scopes qui forceront les requêtes Eloquent à se limiter.

**Exemple : Restreindre les données aux éléments du formateur connecté :**
```php
if(Auth::user()->hasRole('formateur')){
    $this->viewState->init('scope.sessionFormation.filiere.groupes.formateurs.id', $this->sessionState->get('formateur_id'));
}
```

## 4. Modifier dynamiquement les listes dans un formulaire (`dataCalcul`)
Si le choix d'un champ (ex: `filiere_id`) doit limiter les choix d'un autre champ (ex: `session_formation_id`), Gapp utilise le script Ajax `dataCalcul`.
Vous pouvez manipuler l'état depuis le backend dans `dataCalcul` ou un `CalculTrait` en ajoutant des variables au State, qui seront ensuite interceptées pour modifier la liste.

**Exemple d'application dans un Trait (`ProjetCalculTrait.php`) :**
```php
public function dataCalcul($data)
{
    $projet = parent::dataCalcul($data);
    
    // Après le choix de la filière, limiter les sessions de formation
    if (!empty($projet->filiere_id)) {
        // En ajoutant une variable de scope au state, les prochaines requêtes
        // pour récupérer les sessions appliqueront ce filtre automatiquement.
        $viewState = app(\Modules\Core\Services\ViewStateService::class);
        $viewState->set('scope_form.sessionFormation.filiere_id', $projet->filiere_id);
    }
    
    // ...
    return $projet;
}
```
*Note : Si les données du formulaire `sessionFormations` sont pré-chargées via `all()`, s'assurer que le scope défini ci-dessus s'applique bien avant ou via les routes Ajax de données (ex: `getData()`).*

