# Procédure de mise à jour de code 


````bash
sudo git pull
sudo npm install


sudo chmod -R 755 /var/www/app/
sudo chown -R www-data:www-data /var/www/app/
php artisan config:clear
php artisan cache:clear
````

## Validation des noms des classes

````bash
composer dump-autoload
````


## Initialisation de la base de données
````bash
sudo artisan migrate:fresh
sudo artisan db:seed
````