## Ajouter le formateur admin


## ajouter le formateur : admin-formateur

ajouter un formateur pour l'utilisateur : admin-formateur
ajouter le rôle "formateur" à l'utilisateur : admin-formateur

````bash
sudo php artisan migrate
sudo php artisan db:seed --class=Modules\\PkgValidationProjets\\Database\\Seeders\\EtatEvaluationProjetSeeder
sudo php artisan db:seed --class=Modules\\PkgValidationProjets\\Database\\Seeders\\EvaluationRealisationProjetSeeder
````
 
````bash
php artisan migrate
php artisan db:seed --class=Modules\PkgValidationProjets\Database\Seeders\EtatEvaluationProjetSeeder
php artisan db:seed --class=Modules\PkgValidationProjets\Database\Seeders\EvaluationRealisationProjetSeeder
````