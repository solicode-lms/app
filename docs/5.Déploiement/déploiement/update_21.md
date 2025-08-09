
````bash

# Core
php artisan db:seed --class=Modules\Core\Database\Seeders\Base\BaseSysModuleSeeder
php artisan db:seed --class=Modules\Core\Database\Seeders\Base\BaseSysColorSeeder

# PkgAutorisation
php artisan db:seed --class=Modules\PkgAutorisation\Database\Seeders\DefaultApprenantPermission
php artisan db:seed --class=Modules\PkgAutorisation\Database\Seeders\DefaultFormateurPermission

# PkgApprenants
php artisan db:seed --class=Modules\PkgApprenants\Database\Seeders\ApprenantKonosySeeder

# PkgCompetences
php artisan db:seed --class=Modules\PkgCompetences\Database\Seeders\MicroCompetenceSeeder
php artisan db:seed --class=Modules\PkgCompetences\Database\Seeders\UniteApprentissageSeeder
php artisan db:seed --class=Modules\PkgCompetences\Database\Seeders\ChapitreSeeder
php artisan db:seed --class=Modules\PkgCompetences\Database\Seeders\UpdateReferenceSeeder
php artisan db:seed --class=Modules\PkgCompetences\Database\Seeders\PhaseEvaluationSeeder
php artisan db:seed --class=Modules\PkgCompetences\Database\Seeders\CritereEvaluationSeeder

# PkgSessions
php artisan db:seed --class=Modules\PkgSessions\Database\Seeders\SessionFormationSeeder
php artisan db:seed --class=Modules\PkgSessions\Database\Seeders\AlignementUaSeeder

# PkgApprentissage
php artisan db:seed --class=Modules\PkgApprentissage\Database\Seeders\EtatRealisationMicroCompetenceSeeder
php artisan db:seed --class=Modules\PkgApprentissage\Database\Seeders\EtatRealisationUaSeeder
php artisan db:seed --class=Modules\PkgApprentissage\Database\Seeders\EtatRealisationChapitreSeeder

# PkgCreationTache
php artisan db:seed --class=Modules\PkgCreationTache\Database\Seeders\TacheSeeder

# PkgRealisationTache
php artisan db:seed --class=Modules\PkgRealisationTache\Database\Seeders\WorkflowTacheSeeder

# PkgRealisationProjets
php artisan db:seed --class=Modules\PkgRealisationProjets\Database\Seeders\EtatsRealisationProjetSeeder


# PkgWidgets
php artisan db:seed --class=Modules\PkgWidgets\Database\Seeders\WidgetTypeSeeder


````



dans .env


````conf
QUEUE_CONNECTION=sync
````