<?php
// Ce fichier est maintenu par ESSARRAJ Fouad



namespace Modules\PkgRealisationProjets\App\Requests\Base;

use Illuminate\Foundation\Http\FormRequest;

class BaseLivrablesRealisationRequest extends FormRequest
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
            'lien' => 'nullable|max:255',
            'livrable_id' => 'required'
        ];
    }

    public function messages(): array
    {
        return [
            'titre.required' => __('validation.required', ['attribute' => __('PkgRealisationProjets::LivrablesRealisation.titre')]),
            'titre.max' => __('validation.titreMax'),
            'description.required' => __('validation.required', ['attribute' => __('PkgRealisationProjets::LivrablesRealisation.description')]),
            'description.max' => __('validation.descriptionMax'),
            'lien.required' => __('validation.required', ['attribute' => __('PkgRealisationProjets::LivrablesRealisation.lien')]),
            'lien.max' => __('validation.lienMax'),
            'livrable_id.required' => __('validation.required', ['attribute' => __('PkgRealisationProjets::LivrablesRealisation.livrable_id')]),
            'livrable_id.max' => __('validation.livrable_idMax')
        ];
    }
}
