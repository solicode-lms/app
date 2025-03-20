<?php
// Ce fichier est maintenu par ESSARRAJ Fouad



namespace Modules\PkgAutoformation\App\Requests\Base;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Modules\PkgAutoformation\Models\EtatChapitre;

class BaseEtatChapitreRequest extends FormRequest
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
            'workflow_chapitre_id' => 'nullable',
            'sys_color_id' => 'required',
            'is_editable_only_by_formateur' => 'nullable|boolean',
            'description' => 'nullable|string',
            'formateur_id' => 'required'
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
            'nom.required' => __('validation.required', ['attribute' => __('PkgAutoformation::EtatChapitre.nom')]),
            'nom.max' => __('validation.nomMax'),
            'workflow_chapitre_id.required' => __('validation.required', ['attribute' => __('PkgAutoformation::EtatChapitre.workflow_chapitre_id')]),
            'sys_color_id.required' => __('validation.required', ['attribute' => __('PkgAutoformation::EtatChapitre.sys_color_id')]),
            'is_editable_only_by_formateur.required' => __('validation.required', ['attribute' => __('PkgAutoformation::EtatChapitre.is_editable_only_by_formateur')]),
            'description.required' => __('validation.required', ['attribute' => __('PkgAutoformation::EtatChapitre.description')]),
            'formateur_id.required' => __('validation.required', ['attribute' => __('PkgAutoformation::EtatChapitre.formateur_id')])
        ];
    }

    
}
