# 🚀 Guide Spatie Permission : Projet Laravel Standard (Sans Modules)

Ce guide explique comment implémenter et utiliser `spatie/laravel-permission` dans un projet Laravel standard (non-modulaire).

## 1. Installation

```bash
composer require spatie/laravel-permission
php artisan vendor:publish --provider="Spatie\Permission\PermissionServiceProvider"
php artisan migrate
```

## 2. Configuration du Modèle User

Ajoutez le Trait `HasRoles` à votre modèle `User`.

```php
// app/Models/User.php
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    use HasRoles; 
    // ...
}
```

## 3. Création des Rôles et Permissions

L'idéal est de le faire dans un Seeder (`database/seeders/DatabaseSeeder.php`).

```php
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

public function run()
{
    // Créer des permissions
    Permission::create(['name' => 'edit articles']);
    Permission::create(['name' => 'delete articles']);

    // Créer des rôles et assigner des permissions
    $roleWriter = Role::create(['name' => 'writer']);
    $roleWriter->givePermissionTo('edit articles');

    $roleAdmin = Role::create(['name' => 'admin']);
    $roleAdmin->givePermissionTo(['edit articles', 'delete articles']);
    
    // Assigner un rôle à un utilisateur
    $user = User::find(1);
    $user->assignRole('admin');
}
```

## 4. Vérification des Droits (Le Cœur du Sujet)

Il y a 3 façons principales de protéger votre application.

### A. Via le Routage (Middleware) 🛡️

C'est la méthode que vous cherchiez. Spatie fournit des middlewares pour protéger vos routes.

**Étape 1 : Enregistrer les Middlewares**
*Dans Laravel 11 (`bootstrap/app.php`)* :

```php
    ->withMiddleware(function (Middleware $middleware) {
        $middleware->alias([
            'role' => \Spatie\Permission\Middleware\RoleMiddleware::class,
            'permission' => \Spatie\Permission\Middleware\PermissionMiddleware::class,
            'role_or_permission' => \Spatie\Permission\Middleware\RoleOrPermissionMiddleware::class,
        ]);
    })
```

**Étape 2 : Utiliser dans `routes/web.php`**

```php
// Protéger un groupe de routes pour un Rôle spécifique
Route::group(['middleware' => ['role:admin']], function () {
    Route::get('/admin/dashboard', [AdminController::class, 'index']);
});

// Protéger une route unique pour une Permission spécifique
Route::post('/articles', [ArticleController::class, 'store'])
    ->middleware('permission:create articles');

// Plusieurs rôles autorisés (pipe `|`)
Route::get('/dashboard', [DashboardController::class, 'index'])
    ->middleware('role:admin|writer');
```

---

### B. Via les Contrôleurs (Constructeur)

Vous pouvez aussi appliquer la protection directement dans le contrôleur.

```php
class ArticleController extends Controller
{
    public function __construct()
    {
        // Seules les méthodes 'create' et 'store' nécessitent la permission
        $this->middleware('permission:create articles')->only(['create', 'store']);
        
        // Ou pour tout le contrôleur
        // $this->middleware('role:writer');
    }
}
```

---

### C. Via Blade (Frontend)

Pour cacher des boutons aux utilisateurs non autorisés.

```blade
@role('admin')
    <button>Supprimer (Admin Only)</button>
@endrole

@can('edit articles')
    <a href="/edit">Éditer</a>
@else
    <span>Vous ne pouvez pas éditer.</span>
@endcan
```

## Résumé
1.  **Middleware (`routes/web.php`)** : Idéal pour bloquer l'accès complet à des pages/actions. C'est la "porte d'entrée".
2.  **Blade** : Pour adapter l'interface (UI).
3.  **code** : `$user->can('edit articles')` pour des logiques métier complexes.

---

# 🎁 Bonus : L'Approche Automatisée (Style SoliLMS) sans Modules

Si vous souhaitez éviter d'écrire des middlewares partout et avoir une sécurité "par défaut" comme dans SoliLMS, voici comment faire dans un Laravel standard.

## 1. Créer le Middleware Dynamique

Créez `app/Http/Middleware/CheckDynamicPermission.php`.

```php
namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class CheckDynamicPermission
{
    public function handle($request, Closure $next)
    {
        // 1. Récupérer le Controller et la Méthode
        $action = $request->route()->getActionName();
        // Ex: "App\Http\Controllers\ArticleController@edit"
        
        if (!str_contains($action, '@')) {
            return $next($request); // Closure route (non supporté)
        }

        [$controllerClass, $method] = explode('@', $action);
        
        // Nettoyer le nom (ArticleController -> article)
        $controller = class_basename($controllerClass);
        $controller = str_replace('Controller', '', $controller);
        $controller = strtolower($controller); // "article"

        // 2. Calculer la Permission Attendue
        // Convention : {method}-{controller}
        $permission = "{$method}-{$controller}"; 
        // Ex: "edit-article"

        // 3. Vérifier
        if (Auth::check() && !Auth::user()->can($permission)) {
            abort(403, "Accès refusé. Permission requise : $permission");
        }

        return $next($request);
    }
}
```

## 2. L'appliquer Globalement (BaseControllers)

Au lieu de modifier `routes/web.php`, nous allons modifier vos contrôleurs.

Créez un contrôleur parent `app/Http/Controllers/BaseAdminController.php` :

```php
namespace App\Http\Controllers;

use App\Http\Middleware\CheckDynamicPermission;

class BaseAdminController extends Controller
{
    public function __construct()
    {
        // Applique la sécurité à TOUTES les méthodes de tous les enfants
        $this->middleware(CheckDynamicPermission::class);
    }
}
```

## 3. Utilisation

Faites hériter vos contrôleurs de `BaseAdminController`.

```php
// app/Http/Controllers/ArticleController.php
class ArticleController extends BaseAdminController // 👈 Hérite de la sécurité
{
    public function edit($id) {
        // Le middleware va AUTOMATIQUEMENT vérifier la permission 'edit-article'
        // Rien à faire ici !
        return view('articles.edit');
    }
}
```

## 4. Gérer les Exceptions (Optionnel)

Si vous voulez qu'une méthode soit publique, vous pouvez utiliser l'attribut PHP 8 `#[Unprotected]` ou simplement exclure dans le constructeur enfant :

```php
public function __construct()
{
    parent::__construct();
    // Exclure la méthode 'show' de la vérification
    $this->middleware(CheckDynamicPermission::class)->except('show');
}
```
