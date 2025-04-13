## Migration 

````bash
 sudo php artisan migrate
````

- mettre à jour la table : e_metadata_definitions


### Sur Linux

````bash
sudo php artisan db:seed --class=Modules\\PkgWidgets\\Database\\Seeders\\WidgetSeeder
sudo php artisan db:seed --class=Modules\\PkgWidgets\\Database\\Seeders\\SectionWidgetSeeder
sudo php artisan db:seed --class=Modules\\PkgRealisationProjets\\Database\\Seeders\\WorkflowProjetSeeder
sudo php artisan db:seed --class=Modules\\PkgGestionTaches\\Database\\Seeders\\WorkflowTacheSeeder
````

### Sur Windows 

````bash
php artisan db:seed --class=Modules\PkgWidgets\Database\Seeders\WidgetSeeder
php artisan db:seed --class=Modules\PkgWidgets\Database\Seeders\SectionWidgetSeeder
php artisan db:seed --class=Modules\PkgRealisationProjets\Database\Seeders\WorkflowProjetSeeder
php artisan db:seed --class=Modules\PkgGestionTaches\Database\Seeders\WorkflowTacheSeeder
````


## Mettre à jour les états de WorkFlow projet par les formateurs

