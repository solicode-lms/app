<?php
// Ce fichier est maintenu par ESSARRAJ Fouad



namespace Modules\PkgGestionTaches\App\Requests\Base;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Modules\PkgGestionTaches\Models\WorkflowTache;

class BaseWorkflowTacheRequest extends FormRequest
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
            'ordre' => 'nullable|integer',
            'code' => 'required|string|max:255',
            'titre' => 'required|string|max:255',
            'description' => 'nullable|string',
            'sys_color_id' => 'nullable'
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
            'ordre.required' => __('validation.required', ['attribute' => __('PkgGestionTaches::WorkflowTache.ordre')]),
            'code.required' => __('validation.required', ['attribute' => __('PkgGestionTaches::WorkflowTache.code')]),
            'code.max' => __('validation.codeMax'),
            'titre.required' => __('validation.required', ['attribute' => __('PkgGestionTaches::WorkflowTache.titre')]),
            'titre.max' => __('validation.titreMax'),
            'description.required' => __('validation.required', ['attribute' => __('PkgGestionTaches::WorkflowTache.description')]),
            'sys_color_id.required' => __('validation.required', ['attribute' => __('PkgGestionTaches::WorkflowTache.sys_color_id')])
        ];
    }

    
}
