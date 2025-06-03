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

# Ajouter permission pour le rôle : evaluateur

Afficher : tous les objets
Profile : Profile - Édition sans Ajouter
WidgetUtilisateur : WidgetUtilisateur - Édition sans Ajouter 
RealisationTache : RealisationTache - Édition sans Ajouter 


## Droit d'accès 

### Formateur 

- EvaluationRealisationProjet
  - Afficher sur tous les tables
  - EvaluationRealisationProjet - Édition sans Ajouter 
  - EvaluationRealisationProjet - Extraction