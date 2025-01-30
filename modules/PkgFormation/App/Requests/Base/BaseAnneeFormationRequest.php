<?php
// Ce fichier est maintenu par ESSARRAJ Fouad



namespace Modules\PkgFormation\App\Requests\Base;

use Illuminate\Foundation\Http\FormRequest;

class BaseAnneeFormationRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'titre' => 'required|max:255',
            'date_debut' => 'required',
            'date_fin' => 'required'
        ];
    }

    public function messages(): array
    {
        return [
            'titre.required' => __('validation.required', ['attribute' => __('PkgFormation::AnneeFormation.titre')]),
            'titre.max' => __('validation.titreMax'),
            'date_debut.required' => __('validation.required', ['attribute' => __('PkgFormation::AnneeFormation.date_debut')]),
            'date_debut.max' => __('validation.date_debutMax'),
            'date_fin.required' => __('validation.required', ['attribute' => __('PkgFormation::AnneeFormation.date_fin')]),
            'date_fin.max' => __('validation.date_finMax')
        ];
    }
}
