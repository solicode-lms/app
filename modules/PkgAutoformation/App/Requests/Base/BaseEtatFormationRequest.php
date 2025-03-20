<?php
// Ce fichier est maintenu par ESSARRAJ Fouad



namespace Modules\PkgAutoformation\App\Requests\Base;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Modules\PkgAutoformation\Models\EtatFormation;

class BaseEtatFormationRequest extends FormRequest
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
            'nom' => 'required|string|max:255',
            'workflow_formation_id' => 'nullable',
            'sys_color_id' => 'required',
            'is_editable_only_by_formateur' => 'nullable|boolean',
            'formateur_id' => 'required',
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
            'nom.required' => __('validation.required', ['attribute' => __('PkgAutoformation::EtatFormation.nom')]),
            'nom.max' => __('validation.nomMax'),
            'workflow_formation_id.required' => __('validation.required', ['attribute' => __('PkgAutoformation::EtatFormation.workflow_formation_id')]),
            'sys_color_id.required' => __('validation.required', ['attribute' => __('PkgAutoformation::EtatFormation.sys_color_id')]),
            'is_editable_only_by_formateur.required' => __('validation.required', ['attribute' => __('PkgAutoformation::EtatFormation.is_editable_only_by_formateur')]),
            'formateur_id.required' => __('validation.required', ['attribute' => __('PkgAutoformation::EtatFormation.formateur_id')]),
            'description.required' => __('validation.required', ['attribute' => __('PkgAutoformation::EtatFormation.description')])
        ];
    }

    
}
