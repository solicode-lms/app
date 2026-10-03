# Issue : Passage explicite de scopeVariables aux composants anonymes Blade

## Contexte
Actuellement, Gapp génère les vues `_index.blade.php` en incluant le composant anonyme `<x-crud-header>`.
Cependant, les composants anonymes sous Laravel n'héritent pas automatiquement des variables locales définies dans la vue parente.

## Problème
La variable `$scopeVariables` (calculée dans le Service et passée via le Controller dans `$tache_compact_value`) n'est pas transmise au composant `crud-header.blade.php`.
Si l'on essaie de lire l'état du ViewStateService directement à l'intérieur de `crud-header.blade.php`, on crée un couplage fort et on viole le modèle MVC en appelant un Service depuis la couche Blade.

## Solution Proposée (Généralisation Gapp)
Modifier le générateur Gapp pour que TOUS les fichiers `_index.blade.php` (et autres layouts générés) transmettent explicitement les variables d'état (comme `$scopeVariables`) aux composants enfants.

**Modification à apporter dans le template Gapp (pour _index.blade.php) :**
```blade
    <x-crud-header 
        id="{{ $model_name_lower }}-crud-header" 
        icon="fas fa-folder"  
        iconColor="text-info"
        title="{{ ${$model_name_lower . '_title'} }}"
        :breadcrumbs="$breadcrumbs"
        :scopeVariables="$scopeVariables ?? []"
    />
```

Ainsi, l'architecture MVC reste propre :
1. Service : `prepareDataForIndexView` injecte `$scopeVariables` dans le `$data`.
2. Controller : `extract()` rend la variable locale.
3. Blade (`_index.blade.php`) : La passe comme attribut `:scopeVariables`.
4. Composant (`x-crud-header`) : Utilise l'attribut proprement et reste indépendant.

## Fichiers impactés temporairement (Workaround)
Pour tester cette solution en attendant la mise à jour du générateur Gapp, les fichiers générés suivants (qui contiennent la ligne de lock du générateur) ont été modifiés manuellement :

1. **`modules/PkgCreationTache/Services/Base/BaseTacheService.php`**
   - Contient la ligne : `// Ce fichier est maintenu par ESSARRAJ Fouad`
   - **Modification** : Injection de `$scopeVariables` dans le `$compact_value` de la méthode `prepareDataForIndexView()`.
   - **Code ajouté** :
```php
        $scopeVariables = $this->viewState->getScopeVariablesTitles('tache');

        // Préparer les variables à injecter dans compact()
        $compact_value = compact(
            // ...
            'tache_title',
            'contextKey',
            'taches_permissions',
            'taches_permissionsByItem',
            'scopeVariables' // <-- Ajout ici
        );
```

2. **`modules/PkgCreationTache/resources/views/tache/_index.blade.php`**
   - Contient la ligne : `{{-- Ce fichier est maintenu par ESSARRAJ Fouad --}}`
   - **Modification** : Ajout de l'attribut `:scopeVariables="$scopeVariables ?? []"` lors de l'appel au composant `<x-crud-header>`.
   - **Code ajouté** :
```blade
    <x-crud-header 
        id="tache-crud-header" icon="fas fa-tasks"  
        iconColor="text-info"
        title="{{ $tache_title }}"
        :breadcrumbs="[
            ['label' => $package, 'url' => '#'],
            ['label' => $titre]
        ]"
        :scopeVariables="$scopeVariables ?? []" <!-- Ajout ici -->
    />
```

**Action requise post-généralisation :** 
Une fois que Gapp sera mis à jour pour intégrer ce comportement par défaut dans ses templates, une simple régénération (`gapp make:crud Tache`) suffira à réécraser ces deux fichiers de manière transparente sans aucune perte de fonctionnalité.
