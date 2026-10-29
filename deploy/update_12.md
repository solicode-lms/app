# Mise à jour 12 - S2 Filière Développement Mobile (DMB)

## 1. Corrections BDD préalables
- Renommer l'Unité d'Apprentissage `DMB-UA.021.11` vers `DMB-UA.201.11` (référence et code).

### En local (Windows)
```bash
php artisan tinker storage/update_reference_ua.php
```

### En production (Serveur Linux)
```bash
sudo php artisan tinker storage/update_reference_ua.php
```

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

