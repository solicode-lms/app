
## Création de la table labelProjet

`````bash
sudo php artisan migrate
````



`````bash
sudo php artisan db:seed --class=Modules\\PkgCreationProjet\\Database\\Seeders\\LabelProjetSeeder
````


php artisan db:seed --class=Modules\PkgCreationProjet\Database\Seeders\LabelProjetSeeder

## ajouter les droit d'accès 

- ajouter les droit d'éduter pour formateur 
- ajouer droit lecture pour apprenant, et admin