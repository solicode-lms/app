<?php



namespace Modules\PkgRealisationTache\App\Providers;
use Modules\PkgRealisationTache\App\Providers\Base\BasePkgRealisationTacheServiceProvider;
use Modules\PkgRealisationTache\Models\RealisationTache;
use Modules\PkgRealisationTache\Observers\RealisationTacheObserver;

class PkgRealisationTacheServiceProvider extends BasePkgRealisationTacheServiceProvider
{
    
    // public function boot(): void
    // {
    //     parent::boot();
    //     RealisationTache::observe(RealisationTacheObserver::class);
    // }

}
