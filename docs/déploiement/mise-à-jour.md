# Procédure de mise à jour de code 


````bash
sudo git pull
sudo npm install


sudo chmod -R 755 /var/www/app/
sudo chown -R www-data:www-data /var/www/app/
sudo php artisan config:clear
sudo php artisan cache:clear
````

## Validation des noms des classes

````bash
sudo composer dump-autoload
````


## Initialisation de la base de données
````bash
sudo php artisan migrate:fresh
sudo php artisan db:seed
````