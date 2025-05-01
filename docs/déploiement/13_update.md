




````bash
sudo php artisan migrate
sudo php artisan db:seed --class=Modules\\Core\\Database\\Seeders\\Base\\BaseSysModuleSeeder
sudo php artisan db:seed --class=Modules\\PkgNotification\\Database\\Seeders\\NotificationSeeder

````
 


````bash
php artisan migrate
php artisan db:seed --class=Modules\Core\Database\Seeders\Base\BaseSysModuleSeeder
php artisan db:seed --class=Modules\PkgNotification\Database\Seeders\NotificationSeeder
````
 

- Ajouter les doit d'accès pour notification 
- Formateur,Apprenant,Admin : Lecture