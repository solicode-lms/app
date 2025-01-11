<?php
// Ce fichier est maintenu par ESSARRAJ Fouad



namespace Modules\PkgCompetences\App\Requests\Base;

use Illuminate\Foundation\Http\FormRequest;

class BaseModuleRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'description' => 'nullable',
            'filiere_id' => 'required',
            'masse_horaire' => 'required|max:255',
            'nom' => 'required|max:255'
        ];
    }

    public function messages(): array
    {
        return [
            'description.required' => __('validation.required', ['attribute' => __('PkgCompetences::Module.description')]),
            'description.max' => __('validation.descriptionMax'),
            'filiere_id.required' => __('validation.required', ['attribute' => __('PkgCompetences::Module.filiere_id')]),
            'filiere_id.max' => __('validation.filiere_idMax'),
            'masse_horaire.required' => __('validation.required', ['attribute' => __('PkgCompetences::Module.masse_horaire')]),
            'masse_horaire.max' => __('validation.masse_horaireMax'),
            'nom.required' => __('validation.required', ['attribute' => __('PkgCompetences::Module.nom')]),
            'nom.max' => __('validation.nomMax')
        ];
    }
}
