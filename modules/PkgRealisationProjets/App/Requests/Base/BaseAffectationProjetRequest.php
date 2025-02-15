<?php
// Ce fichier est maintenu par ESSARRAJ Fouad



namespace Modules\PkgRealisationProjets\App\Requests\Base;

use Illuminate\Foundation\Http\FormRequest;

class BaseAffectationProjetRequest extends FormRequest
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
            'projet_id' => 'required',
            'groupe_id' => 'required',
            'date_debut' => 'required',
            'date_fin' => 'nullable',
            'description' => 'nullable|string',
            'annee_formation_id' => 'required'
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
            'projet_id.required' => __('validation.required', ['attribute' => __('PkgRealisationProjets::AffectationProjet.projet_id')]),
            'groupe_id.required' => __('validation.required', ['attribute' => __('PkgRealisationProjets::AffectationProjet.groupe_id')]),
            'date_debut.required' => __('validation.required', ['attribute' => __('PkgRealisationProjets::AffectationProjet.date_debut')]),
            'date_fin.required' => __('validation.required', ['attribute' => __('PkgRealisationProjets::AffectationProjet.date_fin')]),
            'description.required' => __('validation.required', ['attribute' => __('PkgRealisationProjets::AffectationProjet.description')]),
            'annee_formation_id.required' => __('validation.required', ['attribute' => __('PkgRealisationProjets::AffectationProjet.annee_formation_id')])
        ];
    }
}
