<?php
// Ce fichier est maintenu par ESSARRAJ Fouad



namespace Modules\PkgRealisationTache\App\Requests\Base;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Modules\PkgRealisationTache\Models\TacheAffectation;

class BaseTacheAffectationRequest extends FormRequest
{
    /**
     * Détermine si l'utilisateur est autorisé à effectuer cette requête.
     *
     * @return bool
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Retourne les règles de validation appliquées aux champs de la requête.
     *
     * @return array
     */
    public function rules(): array
    {
        return [
            'tache_id' => 'required',
            'affectation_projet_id' => 'required',
            'pourcentage_realisation_cache' => 'required',
            'apprenant_live_coding_cache' => 'nullable'
        ];
    }

    /**
     * Retourne les messages de validation associés aux règles.
     *
     * @return array
     */
    public function messages(): array
    {
        return [
            'tache_id.required' => __('validation.required', ['attribute' => __('PkgRealisationTache::TacheAffectation.tache_id')]),
            'affectation_projet_id.required' => __('validation.required', ['attribute' => __('PkgRealisationTache::TacheAffectation.affectation_projet_id')]),
            'pourcentage_realisation_cache.required' => __('validation.required', ['attribute' => __('PkgRealisationTache::TacheAffectation.pourcentage_realisation_cache')]),
            'apprenant_live_coding_cache.required' => __('validation.required', ['attribute' => __('PkgRealisationTache::TacheAffectation.apprenant_live_coding_cache')])
        ];
    }

}
