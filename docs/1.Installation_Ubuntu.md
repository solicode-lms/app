### Tutoriel pour configurer un serveur Ubuntu pour exécuter une application Laravel et un site web statique

#### Étape 1 : Préparation du serveur
1. **Mettre à jour les packages :**
   ```bash
   sudo apt update && sudo apt upgrade -y
   ```

2. **Installer les outils essentiels :**
   ```bash
   sudo apt install curl zip unzip git -y
   ```

#### Étape 2 : Installation des dépendances pour Laravel
1. **Installer PHP et extensions nécessaires :**
   Laravel requiert PHP 8.0 ou supérieur. Installez PHP avec les extensions :
   ```bash
   sudo apt install php-cli php-mbstring php-xml php-bcmath php-curl php-zip php-mysql -y
   ```

2. **Installer Composer :**
   ```bash
   curl -sS https://getcomposer.org/installer | php
   sudo mv composer.phar /usr/local/bin/composer
   ```

#### Étape 3 : Configurer un serveur web

1. **Installer Nginx :**
   ```bash
   sudo apt install nginx -y
   ```

2. **Configurer Nginx pour Laravel :**
   Créez un fichier de configuration pour Laravel.
   ```bash
   sudo nano /etc/nginx/sites-available/laravel
   ```
   Exemple de configuration :
   ```nginx
   server {
       listen 80;
       server_name votre-domaine.com;

       root /var/www/laravel/public;
       index index.php index.html;

       location / {
           try_files $uri $uri/ /index.php?$query_string;
       }

       location ~ \.php$ {
           include snippets/fastcgi-php.conf;
           fastcgi_pass unix:/var/run/php/php8.1-fpm.sock;
           fastcgi_param SCRIPT_FILENAME $document_root$fastcgi_script_name;
           include fastcgi_params;
       }

       location ~ /\.ht {
           deny all;
       }
   }
   ```

3. **Activer la configuration :**
   ```bash
   sudo ln -s /etc/nginx/sites-available/laravel /etc/nginx/sites-enabled/
   sudo systemctl restart nginx
   ```

#### Étape 4 : Installer le site web statique
1. **Créer un répertoire pour le site statique :**
   ```bash
   sudo mkdir -p /var/www/site-statique
   ```

2. **Ajouter les fichiers du site :**
   Placez vos fichiers HTML/CSS/JS dans `/var/www/site-statique`.

3. **Configurer le serveur pour le site :**
   Ajoutez une nouvelle configuration pour Nginx ou Apache :
   - **Pour Nginx** :
     ```nginx
     server {
         listen 80;
         server_name site-statique.com;

         root /var/www/site-statique;
         index index.html;
     }
     ```

4. **Redémarrer le serveur web :**
   ```bash
   sudo systemctl restart nginx  # ou apache2
   ```

#### Étape 5 : Gestion des domaines

- Configurez vos fichiers DNS pour pointer `votre-domaine.com` et `site-statique.com` vers l’adresse IP de votre serveur.
- Utilisez **Certbot** pour ajouter des certificats SSL gratuits :
  ```bash
  sudo apt install certbot python3-certbot-nginx  # pour Nginx
  sudo apt install certbot python3-certbot-apache # pour Apache
  sudo certbot --nginx -d votre-domaine.com
  sudo certbot --apache -d votre-domaine.com
  ```

#### Étape 6 : Déployer Laravel
1. **Cloner votre projet Laravel :**
   ```bash
   git clone https://github.com/votre-repo/laravel.git /var/www/laravel
   ```

2. **Installer les dépendances :**
   ```bash
   cd /var/www/laravel
   composer install
   ```

3. **Configurer `.env` :**
   ```bash
   cp .env.example .env
   php artisan key:generate
   ```

4. **Configurer les permissions :**
   ```bash
   sudo chown -R www-data:www-data /var/www/laravel
   sudo chmod -R 775 /var/www/laravel/storage /var/www/laravel/bootstrap/cache
   ```

5. **Migrer la base de données :**
   ```bash
   php artisan migrate
   ```

Vous avez maintenant un serveur configuré pour exécuter votre application Laravel et héberger un site web statique.