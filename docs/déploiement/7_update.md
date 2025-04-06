
## Migration 

````bash
 php artisan migrate
````

## Mettre à jour les données de sysModules 


### Sur Linux

````bash
sudo php artisan db:seed --class=Modules\\Core\\Database\\Seeders\\Base\\BaseSysModuleSeeder
sudo php artisan db:seed --class=Modules\\PkgWidgets\\Database\\Seeders\\WidgetSeeder
sudo php artisan db:seed --class=Modules\\PkgWidgets\\Database\\Seeders\\WidgetOperationSeeder
````

### Sur Windows 

````bash
php artisan db:seed --class=Modules\Core\Database\Seeders\Base\BaseSysModuleSeeder
php artisan db:seed --class=Modules\PkgWidgets\Database\Seeders\WidgetSeeder
php artisan db:seed --class=Modules\PkgWidgets\Database\Seeders\WidgetOperationSeeder
````

## Donner le droit à tous les rôle pour afficher : 



http://localhost/admin/PkgWidgets/widgetUtilisateurs

Donner les droits : 

- Admin  et formateur : Edition 
- Apprenant : Edition sans ajouter
- Gapp : Edition sans ajouter

