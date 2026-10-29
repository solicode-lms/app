# Ajouter les session Web de S3 à S7


## Problème 
Problème : Il faut supprimer les Tuto et unité supprimer qui n'exsite pas dans les fichier CSV et sont de filiere Développement Mobile.
Il faut détemriner leur Id et les supprimer un par un via un script 



## 2. Exécution des Seeders

### En local (Windows)
```bash
php artisan db:seed --class="Modules\PkgCompetences\Database\Seeders\CompetenceSeeder"

php artisan db:seed --class="Modules\PkgCompetences\Database\Seeders\MicroCompetenceSeeder"
php artisan db:seed --class="Modules\PkgCompetences\Database\Seeders\UniteApprentissageSeeder"
php artisan db:seed --class="Modules\PkgCompetences\Database\Seeders\ChapitreSeeder"
php artisan db:seed --class="Modules\PkgSessions\Database\Seeders\SessionFormationSeeder"
php artisan db:seed --class="Modules\PkgSessions\Database\Seeders\AlignementUaSeeder"
```

### En production (Serveur Linux)
```bash
sudo php artisan db:seed --class="Modules\PkgCompetences\Database\Seeders\CompetenceSeeder"
sudo php artisan db:seed --class="Modules\PkgCompetences\Database\Seeders\MicroCompetenceSeeder"
sudo php artisan db:seed --class="Modules\PkgCompetences\Database\Seeders\UniteApprentissageSeeder"
sudo php artisan db:seed --class="Modules\PkgCompetences\Database\Seeders\ChapitreSeeder"
sudo php artisan db:seed --class="Modules\PkgSessions\Database\Seeders\SessionFormationSeeder"
sudo php artisan db:seed --class="Modules\PkgSessions\Database\Seeders\AlignementUaSeeder"
```

