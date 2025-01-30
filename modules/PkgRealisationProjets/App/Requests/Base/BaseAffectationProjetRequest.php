<?php
// Ce fichier est maintenu par ESSARRAJ Fouad



namespace Modules\PkgRealisationProjets\App\Requests\Base;

use Illuminate\Foundation\Http\FormRequest;

class BaseAffectationProjetRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'date_debut' => 'required',
            'date_fin' => 'nullable',
            'annee_formation_id' => 'required',
            'description' => 'nullable'
        ];
    }

    public function messages(): array
    {
        return [
            'date_debut.required' => __('validation.required', ['attribute' => __('PkgRealisationProjets::AffectationProjet.date_debut')]),
            'date_debut.max' => __('validation.date_debutMax'),
            'date_fin.required' => __('validation.required', ['attribute' => __('PkgRealisationProjets::AffectationProjet.date_fin')]),
            'date_fin.max' => __('validation.date_finMax'),
            'annee_formation_id.required' => __('validation.required', ['attribute' => __('PkgRealisationProjets::AffectationProjet.annee_formation_id')]),
            'annee_formation_id.max' => __('validation.annee_formation_idMax'),
            'description.required' => __('validation.required', ['attribute' => __('PkgRealisationProjets::AffectationProjet.description')]),
            'description.max' => __('validation.descriptionMax')
        ];
    }
}
