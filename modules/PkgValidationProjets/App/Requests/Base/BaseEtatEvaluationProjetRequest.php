<?php
// Ce fichier est maintenu par ESSARRAJ Fouad



namespace Modules\PkgValidationProjets\App\Requests\Base;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Modules\PkgValidationProjets\Models\EtatEvaluationProjet;

class BaseEtatEvaluationProjetRequest extends FormRequest
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
            'ordre' => 'required|integer',
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
            'ordre.required' => __('validation.required', ['attribute' => __('PkgValidationProjets::EtatEvaluationProjet.ordre')]),
            'code.required' => __('validation.required', ['attribute' => __('PkgValidationProjets::EtatEvaluationProjet.code')]),
            'code.max' => __('validation.codeMax'),
            'titre.required' => __('validation.required', ['attribute' => __('PkgValidationProjets::EtatEvaluationProjet.titre')]),
            'titre.max' => __('validation.titreMax'),
            'description.required' => __('validation.required', ['attribute' => __('PkgValidationProjets::EtatEvaluationProjet.description')]),
            'sys_color_id.required' => __('validation.required', ['attribute' => __('PkgValidationProjets::EtatEvaluationProjet.sys_color_id')])
        ];
    }

    
}
