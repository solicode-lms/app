<?php
 


namespace Modules\PkgGestionTaches\App\Requests;
use Modules\PkgGestionTaches\App\Requests\Base\BaseRealisationTacheRequest;
use Modules\PkgGestionTaches\Models\RealisationTache;

class RealisationTacheRequest extends BaseRealisationTacheRequest
{
 /**
     * Règles de validation de la demande,
     * en fusionnant celles du parent et en ajustant 'note'.
     */
    public function rules(): array
    {
        // On récupère d'abord toutes les règles définies dans le BaseRealisationTacheRequest
        $rules = parent::rules();

       

         // Charger l'instance actuelle du modèle (optionnel, selon ton contexte)
        $realisation_tache_id = $this->route('realisationTache'); // Remplace 'model' par le bon paramètre de route
        
       
        $model = RealisationTache::find($realisation_tache_id);


        // On détermine la note maximale autorisée (issue de la tâche liée)
        // Attention : 'tache' doit être chargée avant validation (via route-model binding ou middleware)
        $maxNote =  $model?->tache?->note;

        // On écrase/ajoute la règle pour 'note'
        $rules['note'] = [
            'nullable',
            'numeric',
            'min:0',
            $maxNote !== null ? 'max:'.$maxNote : '',
        ];

        return $rules;
    }
}
