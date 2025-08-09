Voici une version reformulée et structurée :

---

## 1. Optimisation de l’application

Exécutez ces commandes après chaque déploiement ou mise à jour de code pour mettre en cache les configurations, routes, vues, etc. :

```bash
php artisan optimize
php artisan config:cache
php artisan event:cache
php artisan route:cache
php artisan view:cache
```

---

## 2. Nettoyage du cache

Pour supprimer tous les caches (utile avant de déboguer ou après des changements majeurs) :

```bash
php artisan optimize:clear
php artisan config:clear
php artisan event:clear
php artisan route:clear
php artisan view:clear
```

php artisan config:clear
php artisan event:clear
php artisan route:clear
php artisan view:clear

---

## 3. Post-développement (en local / staging)

1. Installer les dépendances PHP et JavaScript :

   ```bash
   composer install
   npm install && npm run dev
   ```
2. (Optionnel) Ajouter la barre de débogage :

   ```bash
   composer require barryvdh/laravel-debugbar --dev
   ```

---

## 4. Post-déploiement sur le serveur de production

1. Installer uniquement les dépendances de production :

   ```bash
   composer install --no-dev --optimize-autoloader
   ```
2. Builder les assets (si nécessaire) :

   ```bash
   npm install && npm run prod
   ```
3. Lancer l’optimisation et le caching :

   ```bash
   php artisan optimize
   ```

---

💡 **Bonnes pratiques**

* Gardez `APP_ENV=production` et `APP_DEBUG=false` en production.
* N’installez jamais les dépendances `--dev` sur votre serveur.
* Versionnez toujours `composer.json` et `composer.lock`, mais **pas** le dossier `vendor`.
