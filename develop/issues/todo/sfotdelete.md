### Qu'est-ce que `$table->softDeletes()` ?

La méthode `$table->softDeletes()` dans Laravel ajoute une colonne spéciale appelée `deleted_at` à votre table. Cette colonne est utilisée pour implémenter la **suppression douce (soft delete)**, qui permet de marquer une ligne comme supprimée sans réellement la supprimer de la base de données.

---

### Structure Générée par `$table->softDeletes()`

Lorsqu'on ajoute `$table->softDeletes()` dans une migration, une colonne supplémentaire `deleted_at` est créée avec le type `timestamp` qui peut être nul (`nullable()`).

**Exemple :**

```php
Schema::create('sys_modules', function (Blueprint $table) {
    $table->id();
    $table->string('name')->unique();
    $table->timestamps();
    $table->softDeletes(); // Ajoute une colonne "deleted_at"
});
```

Cela crée la structure suivante dans la base de données :

| id  | name          | created_at          | updated_at          | deleted_at          |
|------|---------------|---------------------|---------------------|---------------------|
| 1    | User Module   | 2024-12-22 10:00:00| 2024-12-22 10:30:00 | NULL                |
| 2    | Blog Module   | 2024-12-22 10:15:00| 2024-12-22 10:45:00 | 2024-12-22 11:00:00 |

---

### Fonctionnalités Offertes par `softDeletes`

1. **Marquer comme supprimé :**
   - Lorsque vous supprimez un enregistrement via le modèle Eloquent, il met à jour la colonne `deleted_at` au lieu de supprimer la ligne.

2. **Restaurer un enregistrement :**
   - Vous pouvez restaurer un enregistrement supprimé en remettant la valeur de `deleted_at` à `NULL`.

3. **Exclure les Enregistrements Supprimés :**
   - Par défaut, Eloquent ignore les enregistrements où `deleted_at` n’est pas `NULL`.

---

### Utilisation dans Eloquent

Pour que votre modèle prenne en charge la suppression douce, utilisez le trait `SoftDeletes`.

#### Exemple avec le Modèle `SysModule` :

```php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class SysModule extends Model
{
    use SoftDeletes;

    protected $fillable = ['name', 'slug', 'description', 'is_active', 'order', 'version'];
}
```

---

### Opérations Disponibles avec `softDeletes`

1. **Supprimer un Enregistrement (Soft Delete) :**
   ```php
   $module = SysModule::find(1);
   $module->delete(); // Met à jour "deleted_at" avec la date/heure actuelle
   ```

2. **Restaurer un Enregistrement Supprimé :**
   ```php
   $module = SysModule::withTrashed()->find(1);
   $module->restore(); // Met "deleted_at" à NULL
   ```

3. **Supprimer Définitivement un Enregistrement :**
   ```php
   $module = SysModule::withTrashed()->find(1);
   $module->forceDelete(); // Supprime définitivement la ligne de la table
   ```

4. **Lister les Enregistrements Non Supprimés :**
   ```php
   $modules = SysModule::all(); // Par défaut, exclut ceux avec "deleted_at" non NULL
   ```

5. **Inclure les Enregistrements Supprimés dans une Requête :**
   ```php
   $modules = SysModule::withTrashed()->get();
   ```

6. **Lister Uniquement les Enregistrements Supprimés :**
   ```php
   $modules = SysModule::onlyTrashed()->get();
   ```

---

### Cas d'Utilisation

1. **Gestion de Modules Désactivés :**
   - Lorsqu'un module est supprimé, mais que vous voulez conserver les informations pour un éventuel audit ou historique.
   - Excluez les modules supprimés dans les requêtes actives par défaut.

2. **Restauration :**
   - Permet de restaurer un module désactivé si nécessaire sans avoir à recréer les données.

---

### Exemple Complet de Migration avec `$table->softDeletes()`

```php
Schema::create('sys_modules', function (Blueprint $table) {
    $table->id();
    $table->string('name')->unique(); // Nom du module
    $table->string('slug')->unique(); // Identifiant lisible
    $table->text('description')->nullable(); // Description
    $table->boolean('is_active')->default(true); // Statut actif/inactif
    $table->integer('order')->default(0); // Ordre d'affichage
    $table->string('version')->nullable(); // Version
    $table->timestamps(); // created_at et updated_at
    $table->softDeletes(); // Supprime doucement avec deleted_at
});
```

Avec cette structure et les fonctionnalités Eloquent, vous aurez une gestion complète des suppressions douces pour vos modules.