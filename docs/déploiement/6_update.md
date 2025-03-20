## Migration

php artisan migrate

## Ajouter le package Autoformation à la table sys_modules

### Sur Linux

````bash
sudo php artisan db:seed --class=Modules\\Core\\Database\\Seeders\\Base\\BaseSysModuleSeeder
sudo php artisan db:seed --class=Modules\\PkgAutoformation\\Database\\Seeders\\ChapitreSeeder
sudo php artisan db:seed --class=Modules\\PkgAutoformation\\Database\\Seeders\\EtatChapitreSeeder
sudo php artisan db:seed --class=Modules\\PkgAutoformation\\Database\\Seeders\\EtatFormationSeeder
sudo php artisan db:seed --class=Modules\\PkgAutoformation\\Database\\Seeders\\FormationSeeder
sudo php artisan db:seed --class=Modules\\PkgAutoformation\\Database\\Seeders\\RealisationChapitreSeeder
sudo php artisan db:seed --class=Modules\\PkgAutoformation\\Database\\Seeders\\RealisationFormationSeeder
sudo php artisan db:seed --class=Modules\\PkgAutoformation\\Database\\Seeders\\WorkflowChapitreSeeder
sudo php artisan db:seed --class=Modules\\PkgAutoformation\\Database\\Seeders\\WorkflowFormationSeeder

# ajoute les état par défaut pour tous les formateurs 
sudo php artisan db:seed --class=Modules\\PkgAutoformation\\Database\\Seeders\\AddDefaultEtatSeeder
````

### Sur Windows 

````bash
php artisan db:seed --class=Modules\Core\Database\Seeders\Base\BaseSysModuleSeeder
php artisan db:seed --class=Modules\PkgAutoformation\Database\Seeders\ChapitreSeeder
php artisan db:seed --class=Modules\PkgAutoformation\Database\Seeders\EtatChapitreSeeder
php artisan db:seed --class=Modules\PkgAutoformation\Database\Seeders\EtatFormationSeeder
php artisan db:seed --class=Modules\PkgAutoformation\Database\Seeders\FormationSeeder
php artisan db:seed --class=Modules\PkgAutoformation\Database\Seeders\RealisationChapitreSeeder
php artisan db:seed --class=Modules\PkgAutoformation\Database\Seeders\RealisationFormationSeeder
php artisan db:seed --class=Modules\PkgAutoformation\Database\Seeders\WorkflowChapitreSeeder
php artisan db:seed --class=Modules\PkgAutoformation\Database\Seeders\WorkflowFormationSeeder

# ajoute les état par défaut pour tous les formateurs 
php artisan db:seed --class=Modules\PkgAutoformation\Database\Seeders\AddDefaultEtatSeeder

````

## Affectation des droits d'accès 

### Role formateur 
- Etat des chapitre
- Etat des formation
- Formation
- Chapitre
- Realisation chapitre
- Realisation formation

### Rôle apprenant 

- Lecture : Formation
- Realisation formation 
- Realisatio chapitre

### Rôle : Admin foramteur

- Création des formations officiel
- Créatiopn des chapitre offciel
- Gestion des technologie