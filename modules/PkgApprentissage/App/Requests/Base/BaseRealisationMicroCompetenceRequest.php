<?php
// Ce fichier est maintenu par ESSARRAJ Fouad



namespace Modules\PkgApprentissage\App\Requests\Base;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Modules\PkgApprentissage\Models\RealisationMicroCompetence;

class BaseRealisationMicroCompetenceRequest extends FormRequest
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
            'note_cache' => 'required',
            'bareme_cache' => 'required',
            'commentaire_formateur' => 'nullable|string',
            'dernier_update' => 'nullable',
            'apprenant_id' => 'required',
            'micro_competence_id' => 'required',
            'etat_realisation_micro_competence_id' => 'nullable'
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
            'date_debut.required' => __('validation.required', ['attribute' => __('PkgApprentissage::RealisationMicroCompetence.date_debut')]),
            'date_fin.required' => __('validation.required', ['attribute' => __('PkgApprentissage::RealisationMicroCompetence.date_fin')]),
            'progression_cache.required' => __('validation.required', ['attribute' => __('PkgApprentissage::RealisationMicroCompetence.progression_cache')]),
            'note_cache.required' => __('validation.required', ['attribute' => __('PkgApprentissage::RealisationMicroCompetence.note_cache')]),
            'bareme_cache.required' => __('validation.required', ['attribute' => __('PkgApprentissage::RealisationMicroCompetence.bareme_cache')]),
            'commentaire_formateur.required' => __('validation.required', ['attribute' => __('PkgApprentissage::RealisationMicroCompetence.commentaire_formateur')]),
            'dernier_update.required' => __('validation.required', ['attribute' => __('PkgApprentissage::RealisationMicroCompetence.dernier_update')]),
            'apprenant_id.required' => __('validation.required', ['attribute' => __('PkgApprentissage::RealisationMicroCompetence.apprenant_id')]),
            'micro_competence_id.required' => __('validation.required', ['attribute' => __('PkgApprentissage::RealisationMicroCompetence.micro_competence_id')]),
            'etat_realisation_micro_competence_id.required' => __('validation.required', ['attribute' => __('PkgApprentissage::RealisationMicroCompetence.etat_realisation_micro_competence_id')])
        ];
    }

    
}
