
## Mise à jour de la base de données 
````bash
sudo php artisan migrate
sudo php artisan db:seed --class=Modules\\PkgValidationProjets\\Database\\Seeders\\EtatEvaluationProjetSeeder
sudo php artisan db:seed --class=Modules\\PkgValidationProjets\\Database\\Seeders\\EvaluationRealisationProjetSeeder
sudo php artisan db:seed --class=Modules\\PkgValidationProjets\\Database\\Seeders\\EvaluationRealisationTacheSeeder
````
 
````bash
php artisan migrate
php artisan db:seed --class=Modules\PkgValidationProjets\Database\Seeders\EtatEvaluationProjetSeeder
php artisan db:seed --class=Modules\PkgValidationProjets\Database\Seeders\EvaluationRealisationProjetSeeder
php artisan db:seed --class=Modules\PkgValidationProjets\Database\Seeders\EvaluationRealisationTacheSeeder
````

## Supprimer les formateur admin et admin 2 

- s'il existe
- Supprimer le rôle formateur de admin2

## Ajouter le formateur admin
- ajouter un formateur pour l'utilisateur : admin-formateur
- ajouter le rôle "formateur" à l'utilisateur : admin-formateur

## Droit d'accès 
-  Formateur et evaluateur
   - EvaluationRealisationProjet
     - Afficher sur tous les tables
     - EvaluationRealisationProjet - Édition sans Ajouter 
     - EvaluationRealisationProjet - Extraction
   - EvaluationRealisationTache
     - Édition sans Ajouter 



## Donner le rôle "evaluateur" au utilisateurs formateur



## Ajouter les état par défaut de formateur : admin-formateur


- Rôle évaluateur
  - Supprimer le rôle de modifier la réalisation de projet pour le rôle évaluateur

- Rôle admin 
  - ajouter le droit pour voir et éditer les évaluation de réaisation de projet