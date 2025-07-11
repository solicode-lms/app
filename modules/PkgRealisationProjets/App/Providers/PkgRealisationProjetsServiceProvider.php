<?php
// boot : custom



namespace Modules\PkgRealisationProjets\App\Providers;
use Modules\PkgRealisationProjets\App\Providers\Base\BasePkgRealisationProjetsServiceProvider;
use Illuminate\Support\Facades\File;

class PkgRealisationProjetsServiceProvider extends BasePkgRealisationProjetsServiceProvider
{
    public function boot()
    {
        // Charger les migrations
        $this->loadMigrationsFrom(__DIR__ . '/../../../Database/Migrations');

        // Charger les fichiers de routes du module
        $routeFiles = File::allFiles(__DIR__ . '/../../../Routes');
        
        $routeFiles = collect(File::allFiles(__DIR__ . '/../../../Routes'))
        ->sortBy(function ($file) {
            $name = $file->getFilename();
            return match (true) {
                str_contains($name, '.custom.') => 0,
                str_contains($name, '.api.')    => 1,
                default                       => 10,
            };
        });
        
        foreach ($routeFiles as $routeFile) {
            $this->loadRouteFile($routeFile);
        }

        

        // Charger les vues du module
        $this->loadViewsFrom(__DIR__ . '/../../../resources/views', 'PkgRealisationProjets');

        // Charger les fichiers de traduction
        $this->loadTranslationsFrom(
            __DIR__ . '/../../../resources/lang',
            'PkgRealisationProjets'
        );
    }
    
}
