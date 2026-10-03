# Issue : Affichage dynamique des scopeVariables dans la Stats Bar via un Composant DRY

## Contexte
Actuellement, Gapp génère les vues `_index.blade.php` avec une barre de statistiques (`x-crud-stats-summary`).
Afin d'améliorer l'UI/UX, nous souhaitons afficher les filtres de contexte actifs (le scope parent, ex: Projet X) sous forme de badges, juste à côté des statistiques.

## Problème
La variable `$scopeVariables` (calculée dans le Service et passée via le Controller dans `$tache_compact_value`) n'est pas exploitée par défaut dans les templates générés de Gapp. De plus, insérer du code HTML brut pour l'affichage de badges directement dans les vues générées nuit à la maintenabilité (Principe DRY).

## Solution Proposée (Généralisation Gapp)
Créer un composant réutilisable `<x-crud-context-badges>` et modifier le générateur Gapp pour que TOUS les fichiers `_index.blade.php` affichent ce composant dans la section `stats-bar`.

**Modification à apporter dans le template Gapp (pour _index.blade.php) :**
```blade
    <div class="col-sm-8">
        <x-crud-stats-summary
            icon="fas fa-chart-bar text-info"
            :stats="${$model_name_lower . '_stats'}"
        />
        <x-crud-context-badges :scopeVariables="$scopeVariables ?? []" />
    </div>
```

Ainsi, l'architecture MVC reste propre et DRY :
1. Service : `prepareDataForIndexView` injecte `$scopeVariables` dans le `$data`.
2. Controller : `extract()` rend la variable locale.
3. Blade (`_index.blade.php`) : Appelle le composant `<x-crud-context-badges>`.
4. Composant : Gère le rendu conditionnel et le design des badges de filtres.

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
   - **Modification** : Ajout de l'appel au composant `<x-crud-context-badges>` juste après le `<x-crud-stats-summary>`.
   - **Code ajouté** :
```blade
        <x-crud-stats-summary
            icon="fas fa-chart-bar text-info"
            :stats="$taches_stats"
        />
        <x-crud-context-badges :scopeVariables="$scopeVariables ?? []" /> <!-- Ajout ici -->
```

**Action requise post-généralisation :** 
Une fois que Gapp sera mis à jour pour intégrer ce comportement par défaut dans ses templates, une simple régénération (`gapp make:crud Tache`) suffira à réécraser ces deux fichiers de manière transparente sans aucune perte de fonctionnalité.
