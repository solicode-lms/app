## 🎯 Objectif
Simuler l'ajout d'un **projet** par un administrateur :
1. Connexion à l'administration.
2. Ouverture du formulaire.
3. Remplissage des champs.
4. Soumission du formulaire.
5. Vérification de l'ajout.

---

## 🛠️ Étape 1 – Installer Laravel Dusk

### 1.1 Ajouter le package
```bash
composer require --dev laravel/dusk
```

### 1.2 Initialiser Dusk
```bash
php artisan dusk:install
```
Cela crée :
- `tests/Browser/`
- `DuskTestCase.php`
- `.env.dusk.local`

---

## 🐛 Problèmes SSL fréquents

### ✅ Erreur 1 : Avast bloque les connexions HTTPS
> **Symptôme** : `SSL certificate problem: unable to get local issuer certificate`

**Solution** : *Désactive temporairement l’analyse HTTPS* dans Avast (ou l’antivirus concerné).

---

### ✅ Erreur 2 : `cacert.pem` manquant

> **Message** : `cURL error 60: unable to get local issuer certificate`

**Solution** :
1. Télécharge le certificat depuis :  
   [https://curl.se/ca/cacert.pem](https://curl.se/ca/cacert.pem)

2. Place-le dans :

   ```
   C:\php\bin\extras\cacert.pem
   ```

3. Dans `php.ini`, ajoute :
   ```ini
   curl.cainfo = "C:\php\bin\extras\cacert.pem"
   openssl.cafile = "C:\php\bin\extras\cacert.pem"
   ```

4. Redémarre Apache ou le serveur PHP.

---

## 🧪 Étape 2 – Créer un test navigateur

```bash
php artisan dusk:make CreateProjetTest
```

Fichier généré : `tests/Browser/CreateProjetTest.php`

---

## ✍️ Étape 3 – Exemple de test

```php
public function test_ajout_projet_depuis_interface_admin()
{
    $admin = \App\Models\User::factory()->create([
        'email' => 'admin@solicode.test',
    ]);

    $this->browse(function (Browser $browser) use ($admin) {
        $browser->loginAs($admin)
            ->visit('/admin/PkgCreationProjet/projets/create')
            ->assertSee('Ajouter un projet')
            ->type('titre', 'Projet Test UI')
            ->type('description', 'Test automatisé')
            ->select('nature_livrable_id', 1)
            ->press('Enregistrer')
            ->assertPathIs('/admin/PkgCreationProjet/projets')
            ->assertSee('Projet Test UI');
    });
}
```

> ⚠️ Adapte les champs (`titre`, `description`, etc.) à ton formulaire.

---

## ⚙️ Étape 4 – Configuration `.env.dusk.local`

```dotenv
APP_ENV=dusk
APP_URL=http://localhost
DB_CONNECTION=sqlite
DB_DATABASE=:memory:
```

> Tu peux aussi utiliser MySQL avec `DatabaseMigrations`.

---

## ▶️ Étape 5 – Lancer les tests

```bash
php artisan dusk
```

Pour capturer une capture d’écran :
```php
$browser->screenshot('formulaire-projet');
```

---

## 💡 Astuces et bonnes pratiques

- 📷 Captures dans `tests/Browser/screenshots/`
- ⚠️ Logs JS : `tests/Browser/console/`
- 🔄 Pour installer ou corriger ChromeDriver :
  ```bash
  php artisan dusk:chrome-driver
  ```

---

## 🔁 Automatiser la connexion

Crée un composant de page avec :
```bash
php artisan dusk:page Login
```

