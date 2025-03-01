# Procédure de mise à jour de SoliLMS sur le serveur




1. Backup de la base de donnée 


````bash
mysqldump -u root -p solicode_lms > sauvegarde.sql
````


````bash
sudo git pull
sudo npm install


sudo chmod -R 755 /var/www/app/
sudo chown -R www-data:www-data /var/www/app/
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


## Seeders 

- Supprimer les sys_module en doublons 


````bash
# Ajouter le package PkgGestionTaches
php artisan db:seed --class=Modules\Core\Database\Seeders\Base\BaseSysModuleSeeder
php artisan db:seed --class=Modules\PkgGestionTaches\Database\Seeders\EtatRealisationTacheSeeder
````