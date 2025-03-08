
## Sur Linux

````bash
sudo php artisan db:seed --class=Modules\\PkgGestionTaches\\Database\\Seeders\\TacheSeeder
````

## Sur Windows 

````bash
php artisan db:seed --class=Modules\PkgGestionTaches\Database\Seeders\TacheSeeder
````




Il faut ajouter les droit de tache à Apprenant, Admin , Formateur


# Créer un fichier de seeder pour affecter permission getData à tous les rôle qui ont le droit à index