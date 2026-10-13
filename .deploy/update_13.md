# Création de package Tableau de bord



## Création des Tables 


````bash
php artisan migrate
````

````bash
sudo php artisan migrate
````


## Insertion de Module et Droit d'accès

````bash
# on BaseSysModuleSeeder car SysModuleSeeder n'ajoute pas Data "sysModules.csv" dans la base de données
php artisan db:seed --class=Modules\Core\Database\Seeders\Base\BaseSysModuleSeeder
````


````bash
sudo php artisan db:seed --class=Modules\\Core\\Database\\Seeders\\Base\\BaseSysModuleSeeder
````
