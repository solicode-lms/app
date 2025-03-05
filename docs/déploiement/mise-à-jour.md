# Procédure de mise à jour de SoliLMS sur le serveur


1. Backup de la base de donnée 


````bash
sudo mysqldump -u root -p solicode_lms > sauvegarde_05_03_25.sql
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
sudo php artisan db:seed --class=Modules\\Core\\Database\\Seeders\\Base\\BaseSysModuleSeeder

sudo php artisan db:seed --class=Modules\\PkgGestionTaches\\Database\\Seeders\\CommentaireRealisationTacheSeeder


sudo php artisan db:seed --class=Modules\\PkgGestionTaches\\Database\\Seeders\\DependanceTacheSeeder
sudo php artisan db:seed --class=Modules\\PkgGestionTaches\\Database\\Seeders\\EtatRealisationTacheSeeder


sudo php artisan db:seed --class=Modules\\PkgGestionTaches\\Database\\Seeders\\HistoriqueRealisationTacheSeeder
sudo php artisan db:seed --class=Modules\\PkgGestionTaches\\Database\\Seeders\\LabelRealisationTacheSeeder
sudo php artisan db:seed --class=Modules\\PkgGestionTaches\\Database\\Seeders\\PrioriteTacheSeeder
sudo php artisan db:seed --class=Modules\\PkgGestionTaches\\Database\\Seeders\\RealisationTacheSeeder
sudo php artisan db:seed --class=Modules\\PkgGestionTaches\\Database\\Seeders\\TacheSeeder
sudo php artisan db:seed --class=Modules\\PkgGestionTaches\\Database\\Seeders\\TypeDependanceTacheSeeder



````


## Ajouter les droit d'accès au formateur 

- Pkg : Gestion tâches

## Ajouter les droit d'accès au apprenant