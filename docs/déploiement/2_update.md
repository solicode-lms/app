
## Fix SysModel Seeder 


## Sur Linux

````bash
sudo php artisan db:seed --class=Modules\\Core\\Database\\Seeders\\SysModelSeeder
sudo php artisan db:seed --class=Modules\\PkgWidgets\\Database\\Seeders\\WidgetTypeSeeder
sudo php artisan db:seed --class=Modules\\PkgWidgets\\Database\\Seeders\\WidgetSeeder
````

## Sur Windows 
````bash
php artisan db:seed --class=Modules\Core\Database\Seeders\SysModelSeeder
php artisan db:seed --class=Modules\PkgWidgets\Database\Seeders\WidgetTypeSeeder
php artisan db:seed --class=Modules\PkgWidgets\Database\Seeders\WidgetSeeder


````
