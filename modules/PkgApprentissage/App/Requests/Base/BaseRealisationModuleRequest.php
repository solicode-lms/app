<?php
// Ce fichier est maintenu par ESSARRAJ Fouad



namespace Modules\PkgApprentissage\App\Requests\Base;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Modules\PkgApprentissage\Models\RealisationModule;

class BaseRealisationModuleRequest extends FormRequest
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
            'date_debut' => 'nullable',
            'date_fin' => 'nullable',
            'progression_cache' => 'required',
            'note_cache' => 'nullable',
            'bareme_cache' => 'nullable',
            'commentaire_formateur' => 'nullable|string',
            'dernier_update' => 'nullable',
            'apprenant_id' => 'required',
            'module_id' => 'required',
            'etat_realisation_module_id' => 'nullable'
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
            'date_debut.required' => __('validation.required', ['attribute' => __('PkgApprentissage::RealisationModule.date_debut')]),
            'date_fin.required' => __('validation.required', ['attribute' => __('PkgApprentissage::RealisationModule.date_fin')]),
            'progression_cache.required' => __('validation.required', ['attribute' => __('PkgApprentissage::RealisationModule.progression_cache')]),
            'note_cache.required' => __('validation.required', ['attribute' => __('PkgApprentissage::RealisationModule.note_cache')]),
            'bareme_cache.required' => __('validation.required', ['attribute' => __('PkgApprentissage::RealisationModule.bareme_cache')]),
            'commentaire_formateur.required' => __('validation.required', ['attribute' => __('PkgApprentissage::RealisationModule.commentaire_formateur')]),
            'dernier_update.required' => __('validation.required', ['attribute' => __('PkgApprentissage::RealisationModule.dernier_update')]),
            'apprenant_id.required' => __('validation.required', ['attribute' => __('PkgApprentissage::RealisationModule.apprenant_id')]),
            'module_id.required' => __('validation.required', ['attribute' => __('PkgApprentissage::RealisationModule.module_id')]),
            'etat_realisation_module_id.required' => __('validation.required', ['attribute' => __('PkgApprentissage::RealisationModule.etat_realisation_module_id')])
        ];
    }

    
}
