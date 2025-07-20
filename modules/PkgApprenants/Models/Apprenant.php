<?php


namespace Modules\PkgApprenants\Models;

use Carbon\Carbon;
use Modules\Core\App\Traits\HasDynamicAttributes;
use Modules\PkgApprenants\Models\Base\BaseApprenant;
use Modules\PkgFormation\Models\AnneeFormation;
use Modules\PkgRealisationTache\Models\RealisationTache;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Cache;
use Modules\Core\Services\ViewStateService;

class Apprenant extends BaseApprenant
{
    protected $casts = [
        'date_naissance' => 'date', // ou 'date' si vous n’avez pas besoin de l’heure
    ];

   

    protected $with = [
    'user', // composition
    'groupes', // chargement minimal sans sous-relations
    ];

    protected static function booted()
    {
        // Afficher et utiliser seulement les apprenants active, else dans le cas d'un filtre 
        static::addGlobalScope('inactif', function (Builder $builder) {
            // Accès au ViewStateService
            $viewState = app(ViewStateService::class);
    
            // Nom du modèle utilisé pour les filtres (adapter si besoin)
            $modelName = 'apprenant';
    
            // Récupérer les filtres appliqués via le ViewState
            $filters = $viewState->getFilterVariables($modelName);
    
            // Si le filtre 'actif' est défini, ne pas appliquer le scope
            if (array_key_exists('actif', $filters)) {
                return;
            }
    
            // Sinon, appliquer le scope global actif = true
            $builder->where('actif', true);
        });
    }
    


    public function __toString()
    {
        return ($this->nom ?? "") . " " . $this->prenom ?? "";
    }

    public function generateReference(): string
    {
        return $this->matricule ;
    }

    

}
