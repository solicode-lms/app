<?php
// Ce fichier est maintenu par ESSARRAJ Fouad



namespace Modules\PkgApprentissage\App\Requests\Base;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Modules\PkgApprentissage\Models\RealisationUaPrototype;

class BaseRealisationUaPrototypeRequest extends FormRequest
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
            'realisation_tache_id' => 'required',
            'realisation_ua_id' => 'required',
            'note' => 'nullable',
            'bareme' => 'required',
            'date_debut' => 'nullable',
            'date_fin' => 'nullable',
            'remarque_formateur' => 'nullable|string'
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
            'realisation_tache_id.required' => __('validation.required', ['attribute' => __('PkgApprentissage::RealisationUaPrototype.realisation_tache_id')]),
            'realisation_ua_id.required' => __('validation.required', ['attribute' => __('PkgApprentissage::RealisationUaPrototype.realisation_ua_id')]),
            'note.required' => __('validation.required', ['attribute' => __('PkgApprentissage::RealisationUaPrototype.note')]),
            'bareme.required' => __('validation.required', ['attribute' => __('PkgApprentissage::RealisationUaPrototype.bareme')]),
            'date_debut.required' => __('validation.required', ['attribute' => __('PkgApprentissage::RealisationUaPrototype.date_debut')]),
            'date_fin.required' => __('validation.required', ['attribute' => __('PkgApprentissage::RealisationUaPrototype.date_fin')]),
            'remarque_formateur.required' => __('validation.required', ['attribute' => __('PkgApprentissage::RealisationUaPrototype.remarque_formateur')])
        ];
    }

    
}
