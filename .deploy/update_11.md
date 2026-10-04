# Mise à jour 11 - S3 Filière Développement Web

## 1. Résolution de problème de compétence C152
- Réaffectation des micro-compétences de la compétence ID `9` (C152) vers la compétence ID `15` (C152).
- Suppression de la compétence orpheline (ID `9`).

## 2. Exécution des Migrations et Seeders

### En local (Windows)
```bash
# Migration
php artisan migrate

# Insertion de S3 à N1
php artisan db:seed --class="Modules\PkgCompetences\Database\Seeders\CompetenceSeeder"
php artisan db:seed --class="Modules\PkgCompetences\Database\Seeders\MicroCompetenceSeeder"
php artisan db:seed --class="Modules\PkgCompetences\Database\Seeders\UniteApprentissageSeeder"
php artisan db:seed --class="Modules\PkgCompetences\Database\Seeders\ChapitreSeeder"
php artisan db:seed --class="Modules\PkgSessions\Database\Seeders\SessionFormationSeeder"
php artisan db:seed --class="Modules\PkgSessions\Database\Seeders\AlignementUaSeeder"

# Phase évaluation
php artisan db:seed --class="Modules\PkgCompetences\Database\Seeders\PhaseEvaluationSeeder"

# Équipe
php artisan db:seed --class="Modules\PkgCreationProjet\Database\Seeders\EquipeProjetSeeder"
```

### En production (Serveur Linux)
```bash
# Migration
sudo php artisan migrate

# Insertion de S3 à N1
sudo php artisan db:seed --class="Modules\PkgCompetences\Database\Seeders\CompetenceSeeder"
sudo php artisan db:seed --class="Modules\PkgCompetences\Database\Seeders\MicroCompetenceSeeder"
sudo php artisan db:seed --class="Modules\PkgCompetences\Database\Seeders\UniteApprentissageSeeder"
sudo php artisan db:seed --class="Modules\PkgCompetences\Database\Seeders\ChapitreSeeder"
sudo php artisan db:seed --class="Modules\PkgSessions\Database\Seeders\SessionFormationSeeder"
sudo php artisan db:seed --class="Modules\PkgSessions\Database\Seeders\AlignementUaSeeder"

# Phase évaluation
sudo php artisan db:seed --class="Modules\PkgCompetences\Database\Seeders\PhaseEvaluationSeeder"

# Équipe
sudo php artisan db:seed --class="Modules\PkgCreationProjet\Database\Seeders\EquipeProjetSeeder"
```

## 3. Ajouter les droits d'accès
- **Formateur** : Ajouter la permission *EquipeProjet - Édition* (Feature Édition for EquipeProjet).
- **Admin / Apprenant** : Configurer les droits associés.