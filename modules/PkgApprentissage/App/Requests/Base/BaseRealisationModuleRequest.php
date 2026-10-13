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
            'bareme_non_evalue_cache' => 'required',
            'commentaire_formateur' => 'nullable|string',
            'date_debut' => 'nullable',
            'note_cc_officielle' => 'nullable',
            'date_fin' => 'nullable',
            'note_efm_officielle' => 'nullable',
            'statut_docimologique' => 'required|string|max:255',
            'progression_ideal_cache' => 'required',
            'taux_rythme_cache' => 'required',
            'pourcentage_non_valide_cache' => 'required'
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
            'bareme_non_evalue_cache.required' => __('validation.required', ['attribute' => __('PkgApprentissage::RealisationModule.bareme_non_evalue_cache')]),
            'commentaire_formateur.required' => __('validation.required', ['attribute' => __('PkgApprentissage::RealisationModule.commentaire_formateur')]),
            'date_debut.required' => __('validation.required', ['attribute' => __('PkgApprentissage::RealisationModule.date_debut')]),
            'note_cc_officielle.required' => __('validation.required', ['attribute' => __('PkgApprentissage::RealisationModule.note_cc_officielle')]),
            'date_fin.required' => __('validation.required', ['attribute' => __('PkgApprentissage::RealisationModule.date_fin')]),
            'note_efm_officielle.required' => __('validation.required', ['attribute' => __('PkgApprentissage::RealisationModule.note_efm_officielle')]),
            'statut_docimologique.required' => __('validation.required', ['attribute' => __('PkgApprentissage::RealisationModule.statut_docimologique')]),
            'statut_docimologique.max' => __('validation.statut_docimologiqueMax'),
            'progression_ideal_cache.required' => __('validation.required', ['attribute' => __('PkgApprentissage::RealisationModule.progression_ideal_cache')]),
            'taux_rythme_cache.required' => __('validation.required', ['attribute' => __('PkgApprentissage::RealisationModule.taux_rythme_cache')]),
            'pourcentage_non_valide_cache.required' => __('validation.required', ['attribute' => __('PkgApprentissage::RealisationModule.pourcentage_non_valide_cache')])
        ];
    }

}
