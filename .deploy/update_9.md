# Création de package QCM 



## Création des Tables 


````bash
php artisan migrate
````

````bash
sudo php artisan migrate
````


## Insertion de Module et Droit d'accès

````bash
# on BaseSysModuleSeeder car SysModuleSeeder n'ajoute pas Data "sysModules.csv" dans la base de données
php artisan db:seed --class=Modules\Core\Database\Seeders\Base\BaseSysModuleSeeder
php artisan db:seed --class=Modules\PkgQcm\Database\Seeders\AffectationQcmProjetSeeder
php artisan db:seed --class=Modules\PkgQcm\Database\Seeders\EtatRealisationQcmSeeder
php artisan db:seed --class=Modules\PkgQcm\Database\Seeders\PropositionReponseSeeder
php artisan db:seed --class=Modules\PkgQcm\Database\Seeders\QcmSeeder
php artisan db:seed --class=Modules\PkgQcm\Database\Seeders\QuestionSeeder
php artisan db:seed --class=Modules\PkgQcm\Database\Seeders\RealisationQcmSeeder
php artisan db:seed --class=Modules\PkgQcm\Database\Seeders\ReponseQcmSeeder
````


````bash
sudo php artisan db:seed --class=Modules\\Core\\Database\\Seeders\\Base\\BaseSysModuleSeeder
sudo php artisan db:seed --class=Modules\\PkgQcm\\Database\\Seeders\\AffectationQcmProjetSeeder
sudo php artisan db:seed --class=Modules\\PkgQcm\\Database\\Seeders\\EtatRealisationQcmSeeder
sudo php artisan db:seed --class=Modules\\PkgQcm\\Database\\Seeders\\PropositionReponseSeeder
sudo php artisan db:seed --class=Modules\\PkgQcm\\Database\\Seeders\\QcmSeeder
sudo php artisan db:seed --class=Modules\\PkgQcm\\Database\\Seeders\\QuestionSeeder
sudo php artisan db:seed --class=Modules\\PkgQcm\\Database\\Seeders\\RealisationQcmSeeder
sudo php artisan db:seed --class=Modules\\PkgQcm\\Database\\Seeders\\ReponseQcmSeeder
````

## Donner doit d'accès au Apprenant et Formateur 


### Formateur 

- AffectationQcmProjet - Édition
- EtatRealisationQcm - Lecture
- PropositionReponse - Édition
- Qcm - Édition
- Question - Édition
- Question - Extraction
- Question - Import 
- Question - Export
- RealisationQcm - Édition
- ReponseQcm - Lecture


### Apprenant 

- AffectationQcmProjet - Afficher
- EtatRealisationQcm - Afficher
- PropositionReponse - Afficher 
- Qcm - Afficher
- Question - Afficher 
- RealisationQcm - Lecture
- ReponseQcm - Édition sans Ajouter