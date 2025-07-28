
````bash

php artisan db:seed --class=Modules\PkgAutorisation\Database\Seeders\DefaultApprenantPermission
php artisan db:seed --class=Modules\PkgAutorisation\Database\Seeders\DefaultFormateurPermission


php artisan db:seed --class=Modules\PkgCreationTache\Database\Seeders\TacheSeeder
php artisan db:seed --class=Modules\PkgRealisationTache\Database\Seeders\WorkflowTacheSeeder




php artisan db:seed --class=Modules\PkgApprenants\Database\Seeders\ApprenantKonosySeeder



php artisan db:seed --class=Modules\Core\Database\Seeders\Base\BaseSysModuleSeeder



php artisan db:seed --class=Modules\PkgCompetences\Database\Seeders\MicroCompetenceSeeder
php artisan db:seed --class=Modules\PkgCompetences\Database\Seeders\UniteApprentissageSeeder
php artisan db:seed --class=Modules\PkgCompetences\Database\Seeders\ChapitreSeeder
php artisan db:seed --class=Modules\PkgCompetences\Database\Seeders\UpdateReferenceSeeder

php artisan db:seed --class=Modules\PkgCompetences\Database\Seeders\PhaseEvaluationSeeder
php artisan db:seed --class=Modules\PkgCompetences\Database\Seeders\CritereEvaluationSeeder


php artisan db:seed --class=Modules\Core\Database\Seeders\Base\BaseSysColorSeeder
php artisan db:seed --class=Modules\PkgWidgets\Database\Seeders\WidgetTypeSeeder

php artisan db:seed --class=Modules\PkgApprentissage\Database\Seeders\EtatRealisationChapitreSeeder
php artisan db:seed --class=Modules\PkgApprentissage\Database\Seeders\EtatRealisationMicroCompetenceSeeder

php artisan db:seed --class=Modules\PkgApprentissage\Database\Seeders\EtatRealisationUaSeeder



php artisan db:seed --class=Modules\PkgSessions\Database\Seeders\SessionFormationSeeder
php artisan db:seed --class=Modules\PkgSessions\Database\Seeders\AlignementUaSeeder



````

