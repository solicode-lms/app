# Installaiton de Soli-LMS - Localement - Windows


```bash
git clone https://github.com/solicode-lms/app.git
```

## Installer les dépendances & clé d’app

copier le fichier .env.example en .env


```bash
composer install
npm install
php artisan key:generate
```

```bash
php artisan migrate
php artisan migrate:fresh
```

```bash
php artisan db:seed
```

### e) Lancer l’application (dev) & nettoyer les caches

```bash
php artisan serve
php artisan config:clear
php artisan cache:clear
php artisan optimize:clear
```

