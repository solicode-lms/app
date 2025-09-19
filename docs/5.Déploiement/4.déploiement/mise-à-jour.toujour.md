# Procédure de mise à jour de SoliLMS sur le serveur


1. Backup de la base de donnée 


````bash
sudo mysqldump -u root -p solilms-2025 > solilms-2025_16_09_25.sql
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

