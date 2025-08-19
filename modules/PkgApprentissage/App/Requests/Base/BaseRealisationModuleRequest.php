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
            'module_id' => 'required',
            'apprenant_id' => 'required',
            'progression_cache' => 'required',
            'etat_realisation_module_id' => 'nullable',
            'note_cache' => 'nullable',
            'bareme_cache' => 'nullable',
            'dernier_update' => 'nullable',
            'commentaire_formateur' => 'nullable|string',
            'date_debut' => 'nullable',
            'date_fin' => 'nullable',
            'progression_ideal_cache' => 'required',
            'taux_rythme_cache' => 'required'
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
            'module_id.required' => __('validation.required', ['attribute' => __('PkgApprentissage::RealisationModule.module_id')]),
            'apprenant_id.required' => __('validation.required', ['attribute' => __('PkgApprentissage::RealisationModule.apprenant_id')]),
            'progression_cache.required' => __('validation.required', ['attribute' => __('PkgApprentissage::RealisationModule.progression_cache')]),
            'etat_realisation_module_id.required' => __('validation.required', ['attribute' => __('PkgApprentissage::RealisationModule.etat_realisation_module_id')]),
            'note_cache.required' => __('validation.required', ['attribute' => __('PkgApprentissage::RealisationModule.note_cache')]),
            'bareme_cache.required' => __('validation.required', ['attribute' => __('PkgApprentissage::RealisationModule.bareme_cache')]),
            'dernier_update.required' => __('validation.required', ['attribute' => __('PkgApprentissage::RealisationModule.dernier_update')]),
            'commentaire_formateur.required' => __('validation.required', ['attribute' => __('PkgApprentissage::RealisationModule.commentaire_formateur')]),
            'date_debut.required' => __('validation.required', ['attribute' => __('PkgApprentissage::RealisationModule.date_debut')]),
            'date_fin.required' => __('validation.required', ['attribute' => __('PkgApprentissage::RealisationModule.date_fin')]),
            'progression_ideal_cache.required' => __('validation.required', ['attribute' => __('PkgApprentissage::RealisationModule.progression_ideal_cache')]),
            'taux_rythme_cache.required' => __('validation.required', ['attribute' => __('PkgApprentissage::RealisationModule.taux_rythme_cache')])
        ];
    }

}
