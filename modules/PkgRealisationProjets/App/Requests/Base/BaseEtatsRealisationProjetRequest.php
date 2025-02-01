<?php
// Ce fichier est maintenu par ESSARRAJ Fouad



namespace Modules\PkgRealisationProjets\App\Requests\Base;

use Illuminate\Foundation\Http\FormRequest;

class BaseEtatsRealisationProjetRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'titre' => 'required|max:255',
            'description' => 'nullable',
            'formateur_id' => 'required'
        ];
    }

    public function messages(): array
    {
        return [
            'titre.required' => __('validation.required', ['attribute' => __('PkgRealisationProjets::EtatsRealisationProjet.titre')]),
            'titre.max' => __('validation.titreMax'),
            'description.required' => __('validation.required', ['attribute' => __('PkgRealisationProjets::EtatsRealisationProjet.description')]),
            'description.max' => __('validation.descriptionMax'),
            'formateur_id.required' => __('validation.required', ['attribute' => __('PkgRealisationProjets::EtatsRealisationProjet.formateur_id')]),
            'formateur_id.max' => __('validation.formateur_idMax')
        ];
    }
}
