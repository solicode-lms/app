<?php



namespace Modules\PkgRealisationTache\App\Requests;
use Modules\PkgRealisationTache\App\Requests\Base\BaseRealisationTacheRequest;
use Modules\PkgRealisationTache\Models\RealisationTache;

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
        $maxNote = $model?->tache?->note;

        // On écrase/ajoute la règle pour 'note'
        $rules['note'] = [
            'nullable',
            'numeric',
            'min:0',
            $maxNote !== null ? 'max:' . $maxNote : '',
        ];

        return $rules;
    }

    /**
     * Prépare et sanitize les données avant la validation.
     */
    protected function prepareForValidation()
    {
        // 1. On laisse la classe Base faire son travail (qui va potentiellement 
        // injecter une Collection Eloquent si l'utilisateur n'a pas le droit d'édition)
        parent::prepareForValidation();


        // TODO : il faut résoudre le problème pour tous les relations ManyToMany
        // 2. Correction du bug de validation pour la relation ManyToMany "labelProjets"
        // Si la sécurité (sanitizePayloadByRoles) a restauré les données depuis la BDD,
        // $this->labelProjets sera une Collection. Le validateur 'array' va donc échouer.
        // On convertit la Collection en un simple tableau d'IDs pour que la validation passe
        // et que la méthode sync() fonctionne correctement.
        if ($this->has('labelProjets') && $this->labelProjets instanceof \Illuminate\Support\Collection) {
            $this->merge([
                'labelProjets' => $this->labelProjets->pluck('id')->toArray()
            ]);
        }
    }
}
