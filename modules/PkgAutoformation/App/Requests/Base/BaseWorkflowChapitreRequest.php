<?php
// Ce fichier est maintenu par ESSARRAJ Fouad



namespace Modules\PkgAutoformation\App\Requests\Base;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Modules\PkgAutoformation\Models\WorkflowChapitre;

class BaseWorkflowChapitreRequest extends FormRequest
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
            'titre' => 'required|string|max:255',
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
            'code.required' => __('validation.required', ['attribute' => __('PkgAutoformation::WorkflowChapitre.code')]),
            'code.max' => __('validation.codeMax'),
            'titre.required' => __('validation.required', ['attribute' => __('PkgAutoformation::WorkflowChapitre.titre')]),
            'titre.max' => __('validation.titreMax'),
            'description.required' => __('validation.required', ['attribute' => __('PkgAutoformation::WorkflowChapitre.description')])
        ];
    }

    
}
