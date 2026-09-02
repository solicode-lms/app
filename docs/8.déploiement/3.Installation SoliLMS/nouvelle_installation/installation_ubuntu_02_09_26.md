# Guide d’installation de **SoliLMS** - Ubuntu

**Nouvelle installation (from scratch)**

## Cloer le projet

```bash
cd /var/www/
sudo git clone https://github.com/solicode-lms/app.git
sudo mv app solilms-2026
sudo ln -s /var/www/solilms-2026 ~/home/solicode/

```

Renommez ensuite le dossier si besoin, par ex. `/var/www/solilms-2026`.)

## Droit d'accès pour le fonctionnement de git
* **Droits & sécurité Git**

```bash
sudo chown -R solicode:solicode /var/www/solilms-2026
```
## Paramétrage

* **Copier et ajuster l’environnement**

```bash
cd /var/www/solilms-2026
sudo cp .env.example .env
# Éditez .env : APP_URL, APP_ENV, DB_HOST, DB_DATABASE, DB_USERNAME, DB_PASSWORD, etc.
```

## Créer la base de données (ex. branche `main-2024`)

```bash
mysql -u root -p -e "CREATE DATABASE solilms_2027 CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;"
```

## Installer les dépendances & clé d’app

```bash
sudo composer install
sudo npm install
php artisan key:generate
```

### c) Initialiser le schéma

> En dev, la commande suivante **réinitialise** la base (supprime et recrée les tables).

```bash
php artisan migrate:fresh
```

## Injecter des données (seed)


Lancement générique :

```bash
php artisan db:seed
```

## Lancer l’application (dev) & nettoyer les caches

```bash
php artisan serve
php artisan config:clear
php artisan cache:clear
php artisan optimize:clear
```

## Permissions 

```bash
# Option fine (souvent suffisant pour Laravel)
sudo chown -R www-data:www-data storage bootstrap/cache
sudo chmod -R ug+rw storage bootstrap/cache

# Option large (si besoin, à utiliser avec précaution)
sudo chmod -R 755 /var/www/solilms-2026/
sudo chown -R www-data:www-data /var/www/solilms-2026/
```

---


# Procédure de mise à jour de SoliLMS sur le serveur


1. Backup de la base de donnée 


````bash
sudo mysqldump -u root -p solilms-2025 > solilms-2025_30_03_26.sql
````


````bash
sudo chown -R solicode:solicode /var/www/solilms-2025
git reset --hard
sudo git pull
sudo chmod -R 755 /var/www/solilms-2025/
sudo chown -R www-data:www-data /var/www/solilms-2025/


sudo npm install


sudo php artisan config:clear
sudo php artisan cache:clear
sudo php artisan optimize:clear
````

## Validation des noms des classes

````bash
sudo composer dump-autoload
````




## Initialisation de la base de données

````bash
sudo php artisan migrate
````

