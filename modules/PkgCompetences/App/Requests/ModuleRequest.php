<?php
// Ce fichier est maintenu par ESSARRAJ Fouad



namespace Modules\PkgCompetences\App\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ModuleRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'nom' => 'required|max:255',
            'description' => 'required|max:255',
            'masse_horaire' => 'required|max:255',
            'filiere_id' => 'required'
        ];
    }

    public function messages(): array
    {
        return [
            'nom.required' => __('validation.required', ['attribute' => __('PkgBlog::category.nom')]),
            'nom.max' => __('validation.nomMax'),
            'description.required' => __('validation.required', ['attribute' => __('PkgBlog::category.description')]),
            'description.max' => __('validation.descriptionMax'),
            'masse_horaire.required' => __('validation.required', ['attribute' => __('PkgBlog::category.masse_horaire')]),
            'masse_horaire.max' => __('validation.masse_horaireMax'),
            'filiere_id.required' => __('validation.required', ['attribute' => __('PkgBlog::category.filiere_id')]),
            'filiere_id.max' => __('validation.filiere_idMax')
        ];
    }
}