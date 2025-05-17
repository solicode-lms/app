````bash
sudo php artisan migrate
sudo php artisan db:seed --class=Modules\\Core\\Database\\Seeders\\Base\\BaseSysModuleSeeder
sudo php artisan db:seed --class=Modules\\PkgValidationProjets\\Database\\Seeders\\EvaluateurSeeder
sudo php artisan db:seed --class=Modules\\PkgAutorisation\\Database\\Seeders\\Base\\BaseRoleSeeder
````
 
````bash
php artisan migrate
php artisan db:seed --class=Modules\Core\Database\Seeders\Base\BaseSysModuleSeeder
php artisan db:seed --class=Modules\PkgValidationProjets\Database\Seeders\EvaluateurSeeder
php artisan db:seed --class=Modules\PkgAutorisation\Database\Seeders\Base\BaseRoleSeeder
````