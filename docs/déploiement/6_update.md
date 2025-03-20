## Migration

php artisan migrate

## Ajouter le package Autoformation à la table sys_modules

### Sur Linux

````bash
sudo php artisan db:seed --class=Modules\\Core\\Database\\Seeders\\Base\\BaseSysModuleSeeder
````

### Sur Windows 

````bash
php artisan db:seed --class=Modules\Core\Database\Seeders\Base\BaseSysModuleSeeder
````