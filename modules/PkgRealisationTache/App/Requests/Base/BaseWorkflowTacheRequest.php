<?php
// Ce fichier est maintenu par ESSARRAJ Fouad



namespace Modules\PkgRealisationTache\App\Requests\Base;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Modules\PkgRealisationTache\Models\WorkflowTache;

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
            'is_editable_only_by_formateur' => 'nullable|boolean',
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
            'ordre.required' => __('validation.required', ['attribute' => __('PkgRealisationTache::WorkflowTache.ordre')]),
            'code.required' => __('validation.required', ['attribute' => __('PkgRealisationTache::WorkflowTache.code')]),
            'code.max' => __('validation.codeMax'),
            'titre.required' => __('validation.required', ['attribute' => __('PkgRealisationTache::WorkflowTache.titre')]),
            'titre.max' => __('validation.titreMax'),
            'description.required' => __('validation.required', ['attribute' => __('PkgRealisationTache::WorkflowTache.description')]),
            'is_editable_only_by_formateur.required' => __('validation.required', ['attribute' => __('PkgRealisationTache::WorkflowTache.is_editable_only_by_formateur')]),
            'sys_color_id.required' => __('validation.required', ['attribute' => __('PkgRealisationTache::WorkflowTache.sys_color_id')])
        ];
    }

}
