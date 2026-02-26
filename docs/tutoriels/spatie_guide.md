# 🛡️ Guide : Utilisation de Spatie Permission dans SoliLMS

SoliLMS utilise le package `spatie/laravel-permission` pour gérer les rôles et les permissions, mais avec une surcouche spécifique pour automatiser la gestion via des "Features" et des "Domaines".

## 1. Architecture des Permissions

Dans SoliLMS, les permissions ne sont pas créées en vrac. Elles suivent une hiérarchie stricte liée à la structure modulaire :

1.  **SysModule** : Le module (ex: `PkgAutorisation`).
2.  **SysController** : Le contrôleur (ex: `RoleController`).
3.  **FeatureDomain** : Le domaine fonctionnel (ex: `Gestion des Rôles`).
4.  **Feature** : Une fonctionnalité précise (ex: `Afficher Role`, `Édition Role`).
5.  **Permission** : L'action technique (ex: `show-role`, `edit-role`).

### Convention de Nommage
Les permissions sont **toujours** au format : `{action}-{nomControlleur}`
- `index-role`
- `create-role`
- `edit-role`
- `destroy-role`

## 2. Comment sont définis les Rôles et Permissions ?

Tout se passe dans le module `PkgAutorisation`, principalement via les **Seeders**.

### A. Définition Automatique (`BaseRoleSeeder`)
Le fichier `BaseRoleSeeder.php` définit les actions standards pour chaque "Feature".
Par exemple, pour une Feature de type "Édition", il générera automatiquement :
- `index`, `show`, `create`, `store`, `edit`, `update`, `destroy`, `dataCalcul`, `getData`.

### B. Configuration par CSV
Les rôles et leurs associations sont souvent définis dans `modules/PkgAutorisation/Database/data/roles.csv`.
C'est ici qu'on associe des permissions (ou des widgets) à des rôles comme "Admin" ou "Formateur".

## 3. Utilisation dans le Code

### A. Dans les Vues Blade (`@can`)
Utilisez la directive `@can` avec le nom de la permission (kebab-case).

```blade
{{-- Vérification simple --}}
@can('create-projet')
    <a href="{{ route('projets.create') }}">Nouveau Projet</a>
@endcan

{{-- Vérification sur une instance (Policy) --}}
@can('update', $projet)
    <button>Modifier</button>
@endcan
```

### B. Dans les Contrôleurs et Services
Le `User` utilise le trait `HasRoles`.

```php
// Vérifier une permission
if ($user->can('edit-projet')) {
    // ...
}

// Vérifier un rôle
if ($user->hasRole('formateur')) {
    // ...
}
```

### C. Protection des Routes
Les routes sont généralement protégées via des middlewares ou implicitement par les `Policies` générées.

## 4. Ajouter une Nouvelle Permission

Pour ajouter une permission pour une nouvelle fonctionnalité :

1.  **Ne créez pas la permission manuellement** avec `Permission::create()`.
2.  Assurez-vous que votre contrôleur est bien enregistré dans le `SysController` (via le seeder du module).
3.  Exécutez les seeders du module (`PkgAutorisation`) qui va scanner les contrôleurs et générer les permissions manquantes selon les règles du `BaseRoleSeeder`.

## 5. Débogage

Si une permission ne fonctionne pas :
1.  Vérifiez la table `permissions` pour voir si `name` correspond exactement (ex: `edit-projet` vs `edit-projets`).
2.  Vérifiez la table `model_has_roles` pour confirmer que l'utilisateur a le bon rôle.
3.  Vérifiez la table `role_has_permissions` pour voir si le rôle a la permission.
4.  Videz le cache : `php artisan permission:cache-reset`.
