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
            'code' => 'required|string|max:255',
            'nom' => 'required|string|max:255',
            'workflow_chapitre_id' => 'nullable',
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
            'code.required' => __('validation.required', ['attribute' => __('PkgAutoformation::EtatChapitre.code')]),
            'code.max' => __('validation.codeMax'),
            'nom.required' => __('validation.required', ['attribute' => __('PkgAutoformation::EtatChapitre.nom')]),
            'nom.max' => __('validation.nomMax'),
            'workflow_chapitre_id.required' => __('validation.required', ['attribute' => __('PkgAutoformation::EtatChapitre.workflow_chapitre_id')]),
            'description.required' => __('validation.required', ['attribute' => __('PkgAutoformation::EtatChapitre.description')])
        ];
    }

    
}
