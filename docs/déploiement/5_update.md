
# Mise à jour et add tous les permission manquante 

## Sur Linux

````bash
sudo php artisan db:seed --class=Database\\Seeders\\AddAllPermissions
````

## Sur Windows 

````bash
php artisan db:seed --class=Database\Seeders\AddAllPermissions
php artisan db:seed --class=Database\Seeders\AddRoleToGetDataPermissionsSeeder

````



Il faut ajouter les droit de tache à Apprenant, Admin , Formateur


# Créer un fichier de seeder pour affecter permission getData à tous les rôle qui ont le droit à index