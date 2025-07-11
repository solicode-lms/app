<?php
// Ce fichier est maintenu par ESSARRAJ Fouad



namespace Modules\PkgCompetences\App\Requests\Base;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Modules\PkgCompetences\Models\Competence;

class BaseCompetenceRequest extends FormRequest
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
            'code' => 'required|string|max:255',
            'mini_code' => 'nullable|string|max:255',
            'nom' => 'required|string|max:255',
            'module_id' => 'required',
            'description' => 'nullable|string'
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
            'code.required' => __('validation.required', ['attribute' => __('PkgCompetences::Competence.code')]),
            'code.max' => __('validation.codeMax'),
            'mini_code.required' => __('validation.required', ['attribute' => __('PkgCompetences::Competence.mini_code')]),
            'mini_code.max' => __('validation.mini_codeMax'),
            'nom.required' => __('validation.required', ['attribute' => __('PkgCompetences::Competence.nom')]),
            'nom.max' => __('validation.nomMax'),
            'module_id.required' => __('validation.required', ['attribute' => __('PkgCompetences::Competence.module_id')]),
            'description.required' => __('validation.required', ['attribute' => __('PkgCompetences::Competence.description')])
        ];
    }

    
}
