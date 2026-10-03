
# Ajouter S3 au Filiere : Développement Web 

## Résoudre de problème de compétence C152


- Affecration des microCompétence de compététence id-9(C152) à son compétence(C152 , id=15)
- Supprimer la compétence id = 9, 




## Insertion de S3 à N1


```bash
php artisan db:seed --class=Modules\PkgCompetences\Database\Seeders\CompetenceSeeder
php artisan db:seed --class=Modules\PkgCompetences\Database\Seeders\MicroCompetenceSeeder
php artisan db:seed --class=Modules\PkgCompetences\Database\Seeders\UniteApprentissageSeeder
php artisan db:seed --class=Modules\PkgCompetences\Database\Seeders\ChapitreSeeder


# Reste
php artisan db:seed --class=Modules\PkgSessions\Database\Seeders\SessionFormationSeeder
php artisan db:seed --class=Modules\PkgSessions\Database\Seeders\AlignementUaSeeder
```


 
```bash
sudo php artisan db:seed --class=Modules\\PkgCompetences\\Database\\Seeders\\CompetenceSeeder
sudo php artisan db:seed --class=Modules\\PkgCompetences\\Database\\Seeders\\MicroCompetenceSeeder
sudo php artisan db:seed --class=Modules\\PkgCompetences\\Database\\Seeders\\UniteApprentissageSeeder
sudo php artisan db:seed --class=Modules\\PkgCompetences\\Database\\Seeders\\ChapitreSeeder
sudo php artisan db:seed --class=Modules\\PkgSessions\\Database\\Seeders\\SessionFormationSeeder
sudo php artisan db:seed --class=Modules\\PkgSessions\\Database\\Seeders\\AlignementUaSeeder
```

