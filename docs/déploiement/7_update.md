
## Migration 

````bash
 php artisan migrate
````

## Mettre à jour les données de sysModules 


### Sur Linux

````bash
sudo php artisan db:seed --class=Modules\\Core\\Database\\Seeders\\Base\\BaseSysModuleSeeder
sudo php artisan db:seed --class=Modules\\PkgWidgets\\Database\\Seeders\\WidgetSeeder
````

### Sur Windows 

````bash
php artisan db:seed --class=Modules\Core\Database\Seeders\Base\BaseSysModuleSeeder
php artisan db:seed --class=Modules\PkgWidgets\Database\Seeders\WidgetSeeder
````