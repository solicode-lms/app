<?php
// Ce fichier est maintenu par ESSARRAJ Fouad



namespace Modules\PkgAutoformation\App\Requests\Base;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Modules\PkgAutoformation\Models\RealisationChapitre;

class BaseRealisationChapitreRequest extends FormRequest
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
            'chapitre_id' => 'required',
            'realisation_formation_id' => 'required',
            'etat_chapitre_id' => 'nullable'
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
            'date_debut.required' => __('validation.required', ['attribute' => __('PkgAutoformation::RealisationChapitre.date_debut')]),
            'date_fin.required' => __('validation.required', ['attribute' => __('PkgAutoformation::RealisationChapitre.date_fin')]),
            'chapitre_id.required' => __('validation.required', ['attribute' => __('PkgAutoformation::RealisationChapitre.chapitre_id')]),
            'realisation_formation_id.required' => __('validation.required', ['attribute' => __('PkgAutoformation::RealisationChapitre.realisation_formation_id')]),
            'etat_chapitre_id.required' => __('validation.required', ['attribute' => __('PkgAutoformation::RealisationChapitre.etat_chapitre_id')])
        ];
    }

    
}
