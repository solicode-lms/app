<?php
// Ce fichier est maintenu par ESSARRAJ Fouad



namespace Modules\PkgRealisationProjets\App\Requests\Base;

use Illuminate\Foundation\Http\FormRequest;

class BaseRealisationProjetRequest extends FormRequest
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
            'rapport' => 'nullable',
            'etats_realisation_projet_id' => 'required',
            'apprenant_id' => 'required',
            'affectation_projet_id' => 'required'
        ];
    }

    public function messages(): array
    {
        return [
            'date_debut.required' => __('validation.required', ['attribute' => __('PkgRealisationProjets::RealisationProjet.date_debut')]),
            'date_debut.max' => __('validation.date_debutMax'),
            'date_fin.required' => __('validation.required', ['attribute' => __('PkgRealisationProjets::RealisationProjet.date_fin')]),
            'date_fin.max' => __('validation.date_finMax'),
            'rapport.required' => __('validation.required', ['attribute' => __('PkgRealisationProjets::RealisationProjet.rapport')]),
            'rapport.max' => __('validation.rapportMax'),
            'etats_realisation_projet_id.required' => __('validation.required', ['attribute' => __('PkgRealisationProjets::RealisationProjet.etats_realisation_projet_id')]),
            'etats_realisation_projet_id.max' => __('validation.etats_realisation_projet_idMax'),
            'apprenant_id.required' => __('validation.required', ['attribute' => __('PkgRealisationProjets::RealisationProjet.apprenant_id')]),
            'apprenant_id.max' => __('validation.apprenant_idMax'),
            'affectation_projet_id.required' => __('validation.required', ['attribute' => __('PkgRealisationProjets::RealisationProjet.affectation_projet_id')]),
            'affectation_projet_id.max' => __('validation.affectation_projet_idMax')
        ];
    }
}
