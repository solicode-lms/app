


````bash
php artisan migrate
````


# Mise à jour et add tous les permission manquante 

## Sur Linux

````bash
sudo php artisan db:seed --class=Database\\Seeders\\AddAllPermissions
sudo php artisan db:seed --class=Database\\Seeders\\AddRoleToGetDataPermissionsSeeder
````

## Sur Windows 

````bash
php artisan db:seed --class=Database\Seeders\AddAllPermissions
php artisan db:seed --class=Database\Seeders\AddRoleToGetDataPermissionsSeeder

````


- Il faut ajouter le droit Afficher à tous pour tous les model possible , car il est utiliser pour getData par filter
- Il faut ajouter le droit Afficher : Tache pour : Apprenant, Admin 

