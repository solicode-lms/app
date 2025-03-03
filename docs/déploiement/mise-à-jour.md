# Procédure de mise à jour de SoliLMS sur le serveur


1. Backup de la base de donnée 


````bash
sudo mysqldump -u root -p solicode_lms > sauvegarde_03_03_25.sql
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
# Ajouter les seeders et droit d'accès
php artisan db:seed --class=Modules\Core\Database\Seeders\Base\BaseSysModuleSeeder
php artisan db:seed --class=Modules\PkgGestionTaches\Database\Seeders\CommentaireRealisationTacheSeeder
php artisan db:seed --class=Modules\PkgGestionTaches\Database\Seeders\DependanceTacheSeeder
php artisan db:seed --class=Modules\PkgGestionTaches\Database\Seeders\EtatRealisationTacheSeeder
php artisan db:seed --class=Modules\PkgGestionTaches\Database\Seeders\HistoriqueRealisationTacheSeeder
php artisan db:seed --class=Modules\PkgGestionTaches\Database\Seeders\LabelRealisationTacheSeeder
php artisan db:seed --class=Modules\PkgGestionTaches\Database\Seeders\PrioriteTacheSeeder
php artisan db:seed --class=Modules\PkgGestionTaches\Database\Seeders\RealisationTacheSeeder
php artisan db:seed --class=Modules\PkgGestionTaches\Database\Seeders\TacheSeeder
php artisan db:seed --class=Modules\PkgGestionTaches\Database\Seeders\TypeDependanceTacheSeeder



````


## Ajouter les droit d'accès au formateur 

- Pkg : Gestion tâches

## Ajouter les droit d'accès au apprenant