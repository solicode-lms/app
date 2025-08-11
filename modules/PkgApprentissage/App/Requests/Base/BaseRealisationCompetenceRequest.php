<?php
// Ce fichier est maintenu par ESSARRAJ Fouad



namespace Modules\PkgApprentissage\App\Requests\Base;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Modules\PkgApprentissage\Models\RealisationCompetence;

class BaseRealisationCompetenceRequest extends FormRequest
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
            'realisation_module_id' => 'required',
            'competence_id' => 'required',
            'etat_realisation_competence_id' => 'nullable'
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
            'date_debut.required' => __('validation.required', ['attribute' => __('PkgApprentissage::RealisationCompetence.date_debut')]),
            'date_fin.required' => __('validation.required', ['attribute' => __('PkgApprentissage::RealisationCompetence.date_fin')]),
            'progression_cache.required' => __('validation.required', ['attribute' => __('PkgApprentissage::RealisationCompetence.progression_cache')]),
            'note_cache.required' => __('validation.required', ['attribute' => __('PkgApprentissage::RealisationCompetence.note_cache')]),
            'bareme_cache.required' => __('validation.required', ['attribute' => __('PkgApprentissage::RealisationCompetence.bareme_cache')]),
            'commentaire_formateur.required' => __('validation.required', ['attribute' => __('PkgApprentissage::RealisationCompetence.commentaire_formateur')]),
            'dernier_update.required' => __('validation.required', ['attribute' => __('PkgApprentissage::RealisationCompetence.dernier_update')]),
            'apprenant_id.required' => __('validation.required', ['attribute' => __('PkgApprentissage::RealisationCompetence.apprenant_id')]),
            'realisation_module_id.required' => __('validation.required', ['attribute' => __('PkgApprentissage::RealisationCompetence.realisation_module_id')]),
            'competence_id.required' => __('validation.required', ['attribute' => __('PkgApprentissage::RealisationCompetence.competence_id')]),
            'etat_realisation_competence_id.required' => __('validation.required', ['attribute' => __('PkgApprentissage::RealisationCompetence.etat_realisation_competence_id')])
        ];
    }

    
}
