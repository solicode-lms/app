## Migration 

````bash
 sudo php artisan migrate
````

- mettre à jour la table : e_metadata_definitions


### Sur Linux

````bash
sudo php artisan db:seed --class=Modules\\PkgWidgets\\Database\\Seeders\\WidgetSeeder
sudo php artisan db:seed --class=Modules\\PkgWidgets\\Database\\Seeders\\SectionWidgetSeeder
````

### Sur Windows 

````bash
php artisan db:seed --class=Modules\PkgWidgets\Database\Seeders\WidgetSeeder
php artisan db:seed --class=Modules\PkgWidgets\Database\Seeders\SectionWidgetSeeder
````
