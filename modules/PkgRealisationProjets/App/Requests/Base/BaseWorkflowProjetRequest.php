<?php
// Ce fichier est maintenu par ESSARRAJ Fouad



namespace Modules\PkgRealisationProjets\App\Requests\Base;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Modules\PkgRealisationProjets\Models\WorkflowProjet;

class BaseWorkflowProjetRequest extends FormRequest
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
            'sys_color_id' => 'required',
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
            'ordre.required' => __('validation.required', ['attribute' => __('PkgRealisationProjets::WorkflowProjet.ordre')]),
            'code.required' => __('validation.required', ['attribute' => __('PkgRealisationProjets::WorkflowProjet.code')]),
            'code.max' => __('validation.codeMax'),
            'titre.required' => __('validation.required', ['attribute' => __('PkgRealisationProjets::WorkflowProjet.titre')]),
            'titre.max' => __('validation.titreMax'),
            'sys_color_id.required' => __('validation.required', ['attribute' => __('PkgRealisationProjets::WorkflowProjet.sys_color_id')]),
            'description.required' => __('validation.required', ['attribute' => __('PkgRealisationProjets::WorkflowProjet.description')])
        ];
    }

    
}
