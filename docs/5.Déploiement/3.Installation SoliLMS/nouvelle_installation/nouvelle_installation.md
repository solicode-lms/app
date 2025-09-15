# Guide d’installation de **SoliLMS**

Il existe **deux types d’installation** :

1. **Nouvelle installation (from scratch)**
2. **Installation à partir d’une sauvegarde SQL**

---

## Préliminaires

* **Cloner le projet**

  ```bash
  git clone https://github.com/solicode-lms/app.git
  ```

  (Renommez ensuite le dossier si besoin, par ex. `/var/www/solilms-v2024`.)

* **Droits & sécurité Git (au choix)**

  * **Option A – Changer le propriétaire vers `solicode` (recommandé en dev)**

    ```bash
    sudo chown -R solicode:solicode /var/www/solilms-v2025
    ```
  * **Option B – Garder `root` propriétaire mais marquer le repo comme “safe” pour Git**

    ```bash
    git config --global --add safe.directory /var/www/solilms-v2024
    ```

* **Copier et ajuster l’environnement**

  ```bash
  cd /var/www/solilms-v2024
  sudo cp .env.example .env
  # Éditez .env : APP_URL, APP_ENV, DB_HOST, DB_DATABASE, DB_USERNAME, DB_PASSWORD, etc.
  ```

---

## 1) Nouvelle installation (base vide)

### a) Créer la base de données (ex. branche `main-2024`)

```bash
mysql -u root -p -e "CREATE DATABASE solilms_v2024 CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;"
```

### b) Installer les dépendances & clé d’app

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

Laravel va :

1. **Charger le dump de schéma** (`database/schema/mysql-schema.dump`),
2. Exécuter **uniquement les migrations postérieures au dump**.

### d) Injecter des données (seed)

* Pendant le développement, vous pouvez activer/désactiver les seeders **module par module** via le fichier `modules-config.json` (ou `modules.json` selon votre repo).
* **Modules obligatoires** à prévoir d’office :

  * `Core`
  * `PkgAutorisation`
  * `PkgWidgets`

Lancement générique :

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

### f) Permissions (si déploiement web)

```bash
# Option fine (souvent suffisant pour Laravel)
sudo chown -R www-data:www-data storage bootstrap/cache
sudo chmod -R ug+rw storage bootstrap/cache

# Option large (si besoin, à utiliser avec précaution)
sudo chmod -R 755 /var/www/solilms-v2024/
sudo chown -R www-data:www-data /var/www/solilms-v2024/
```

---

## 2) Installation **à partir d’une sauvegarde SQL**

### a) Créer (ou vider) la base

```bash
mysql -u root -p -e "CREATE DATABASE solilms_v2024 CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;"
# Si vous partez d'une base déjà liée à l'app et souhaitez la vider :
php artisan db:wipe   # ⚠️ supprime toutes les tables de la base ciblée par .env
```

### b) Restaurer la sauvegarde

> Remplacez le nom du fichier par votre dump réel.

```bash
mysql -u root -p --default-character-set=utf8 solilms_v2024 < sauvegarde_30_06_25.sql
# ou par ex. : /backup_db/sauvegarde_20_06_25.sql
```

### c) Dépendances, clé d’app & migrations complémentaires

```bash
sudo composer install
sudo npm install
php artisan key:generate

# Appliquer les migrations manquantes par rapport au dump importé :
php artisan migrate
```

### d) Nettoyage caches & lancement

```bash
php artisan config:clear
php artisan cache:clear
php artisan optimize:clear
php artisan serve
```

### e) Permissions (si déploiement web)

```bash
sudo chown -R www-data:www-data storage bootstrap/cache
sudo chmod -R ug+rw storage bootstrap/cache
```

---

## Aide-mémoire (cheat sheet)

* **Créer DB**
  `mysql -u root -p -e "CREATE DATABASE solilms_v2024 CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;"`
* **Nouvelle install**
  `composer install` → `npm install` → `php artisan key:generate` → `php artisan migrate:fresh` → `php artisan db:seed`
* **Depuis sauvegarde**
  `mysql ... < sauvegarde_YYYY_MM_DD.sql` → `php artisan migrate`
* **Utilitaires**

  * Vider toutes les tables : `php artisan db:wipe`
  * Caches : `php artisan config:clear && php artisan cache:clear && php artisan optimize:clear`
  * Démarrer (dev) : `php artisan serve`

si vous voulez, je peux aussi vous fournir une version “prête à copier-coller” pour votre README.md.
