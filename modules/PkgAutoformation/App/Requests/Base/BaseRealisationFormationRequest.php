<?php
// Ce fichier est maintenu par ESSARRAJ Fouad



namespace Modules\PkgAutoformation\App\Requests\Base;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Modules\PkgAutoformation\Models\RealisationFormation;

class BaseRealisationFormationRequest extends FormRequest
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
            'date_debut' => 'required',
            'date_fin' => 'nullable',
            'formation_id' => 'required',
            'apprenant_id' => 'required',
            'etat_formation_id' => 'nullable'
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
            'date_debut.required' => __('validation.required', ['attribute' => __('PkgAutoformation::RealisationFormation.date_debut')]),
            'date_fin.required' => __('validation.required', ['attribute' => __('PkgAutoformation::RealisationFormation.date_fin')]),
            'formation_id.required' => __('validation.required', ['attribute' => __('PkgAutoformation::RealisationFormation.formation_id')]),
            'apprenant_id.required' => __('validation.required', ['attribute' => __('PkgAutoformation::RealisationFormation.apprenant_id')]),
            'etat_formation_id.required' => __('validation.required', ['attribute' => __('PkgAutoformation::RealisationFormation.etat_formation_id')])
        ];
    }

    
}
