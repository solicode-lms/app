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
            'competence_id' => 'required',
            'realisation_module_id' => 'required',
            'apprenant_id' => 'required',
            'progression_cache' => 'required',
            'note_cache' => 'nullable',
            'etat_realisation_competence_id' => 'nullable',
            'bareme_cache' => 'nullable',
            'dernier_update' => 'nullable',
            'commentaire_formateur' => 'nullable|string',
            'date_debut' => 'nullable',
            'date_fin' => 'nullable'
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
            'competence_id.required' => __('validation.required', ['attribute' => __('PkgApprentissage::RealisationCompetence.competence_id')]),
            'realisation_module_id.required' => __('validation.required', ['attribute' => __('PkgApprentissage::RealisationCompetence.realisation_module_id')]),
            'apprenant_id.required' => __('validation.required', ['attribute' => __('PkgApprentissage::RealisationCompetence.apprenant_id')]),
            'progression_cache.required' => __('validation.required', ['attribute' => __('PkgApprentissage::RealisationCompetence.progression_cache')]),
            'note_cache.required' => __('validation.required', ['attribute' => __('PkgApprentissage::RealisationCompetence.note_cache')]),
            'etat_realisation_competence_id.required' => __('validation.required', ['attribute' => __('PkgApprentissage::RealisationCompetence.etat_realisation_competence_id')]),
            'bareme_cache.required' => __('validation.required', ['attribute' => __('PkgApprentissage::RealisationCompetence.bareme_cache')]),
            'dernier_update.required' => __('validation.required', ['attribute' => __('PkgApprentissage::RealisationCompetence.dernier_update')]),
            'commentaire_formateur.required' => __('validation.required', ['attribute' => __('PkgApprentissage::RealisationCompetence.commentaire_formateur')]),
            'date_debut.required' => __('validation.required', ['attribute' => __('PkgApprentissage::RealisationCompetence.date_debut')]),
            'date_fin.required' => __('validation.required', ['attribute' => __('PkgApprentissage::RealisationCompetence.date_fin')])
        ];
    }

}
