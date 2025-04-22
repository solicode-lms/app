Voici un **tutoriel complet** pour installer et utiliser **Laravel Dusk** afin de tester l’**ajout d’un projet** via l’interface utilisateur.

---

## ✅ Objectif  
Simuler un vrai utilisateur dans un navigateur qui :
1. Se connecte à l’administration.
2. Ouvre le formulaire d’ajout de projet.
3. Remplit les champs requis.
4. Soumet le formulaire.
5. Vérifie que le projet est bien visible dans la liste.

---

## 🛠️ Étape 1 : Installer Laravel Dusk

### 1.1 Ajouter le package

```bash
composer require --dev laravel/dusk
```

### 1.2 Initialiser Dusk

```bash
php artisan dusk:install
```

Cela crée :
- le dossier `tests/Browser`
- la classe de base `DuskTestCase`
- le fichier `.env.dusk.local` pour les tests

---

## 🧪 Étape 2 : Créer un test navigateur

```bash
php artisan dusk:make CreateProjetTest
```

Cela génère le fichier : `tests/Browser/CreateProjetTest.php`

---

## ✍️ Étape 3 : Écrire le test

Voici un exemple de test qui remplit un formulaire de projet :

```php
namespace Tests\Browser;

use Tests\DuskTestCase;
use Laravel\Dusk\Browser;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use App\Models\User;

class CreateProjetTest extends DuskTestCase
{
    use DatabaseMigrations;

    public function test_ajout_projet_depuis_interface_admin()
    {
        // 🔧 Créer un utilisateur ayant accès
        $admin = User::factory()->create([
            'email' => 'admin@solicode.test',
        ]);

        $this->browse(function (Browser $browser) use ($admin) {
            $browser->loginAs($admin)
                ->visit('/admin/PkgCreationProjet/projets/create')
                ->assertSee('Ajouter un projet') // Modifier selon ton interface
                ->type('titre', 'Projet Test UI')
                ->type('description', 'Projet de test automatisé')
                ->select('nature_livrable_id', 1) // Modifier selon ton formulaire
                ->press('Enregistrer')
                ->assertPathIs('/admin/PkgCreationProjet/projets') // Redirection attendue
                ->assertSee('Projet Test UI');
        });
    }
}
```

> 🔁 Adapte les noms des champs (`titre`, `description`, `nature_livrable_id`) selon ton Blade ou Vue.js.

---

## ⚙️ Étape 4 : Configurer `.env.dusk.local`

Crée ce fichier si besoin, avec une config de test légère :

```dotenv
APP_ENV=dusk
APP_URL=http://localhost
DB_CONNECTION=sqlite
DB_DATABASE=:memory:
```

> Ou utilise une base MySQL dédiée `solicode_test` avec rollback automatique (`use DatabaseMigrations`).

---

## ▶️ Étape 5 : Lancer les tests

```bash
php artisan dusk
```

📸 Tu peux générer une capture à un moment :

```php
$browser->screenshot('formulaire-projet');
```

---

## 💡 Astuces

- Pour déboguer : ouvre `tests/Browser/screenshots/` et `console/`.
- Si `chrome` ne se lance pas, installe **ChromeDriver** adapté :
  ```bash
  php artisan dusk:chrome-driver
  ```

---

## 📦 Recommandations

- Utilise `DatabaseMigrations` pour un test isolé propre.
- Utilise `assertPathIs`, `assertSee`, `type`, `select`, `press`, `screenshot`.
- Regroupe les actions répétitives (ex : login) dans des composants (ex: `Pages` ou `Components`).

---

Souhaites-tu que je t’aide à générer un test Dusk dynamique en lisant automatiquement les champs depuis le formulaire HTML ?


## Documentation 

````bash
php artisan dusk:page Login
````