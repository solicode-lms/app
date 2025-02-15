<?php
// Ce fichier est maintenu par ESSARRAJ Fouad



namespace Modules\PkgRealisationProjets\App\Requests\Base;

use Illuminate\Foundation\Http\FormRequest;

class BaseRealisationProjetRequest extends FormRequest
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
            'rapport' => 'nullable|string',
            'etats_realisation_projet_id' => 'nullable',
            'apprenant_id' => 'required',
            'affectation_projet_id' => 'required'
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
            'date_debut.required' => __('validation.required', ['attribute' => __('PkgRealisationProjets::RealisationProjet.date_debut')]),
            'date_fin.required' => __('validation.required', ['attribute' => __('PkgRealisationProjets::RealisationProjet.date_fin')]),
            'rapport.required' => __('validation.required', ['attribute' => __('PkgRealisationProjets::RealisationProjet.rapport')]),
            'etats_realisation_projet_id.required' => __('validation.required', ['attribute' => __('PkgRealisationProjets::RealisationProjet.etats_realisation_projet_id')]),
            'apprenant_id.required' => __('validation.required', ['attribute' => __('PkgRealisationProjets::RealisationProjet.apprenant_id')]),
            'affectation_projet_id.required' => __('validation.required', ['attribute' => __('PkgRealisationProjets::RealisationProjet.affectation_projet_id')])
        ];
    }
}
