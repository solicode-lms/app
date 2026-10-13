<?php
// Ce fichier est maintenu par ESSARRAJ Fouad



namespace Modules\PkgTableauBord\App\Requests\Base;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Modules\PkgTableauBord\Models\Remediation;

class BaseRemediationRequest extends FormRequest
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
            'realisation_tache_id' => 'required',
            'formateur_id' => 'required',
            'type_remediation' => 'required|string|max:255',
            'diagnostic' => 'nullable|string',
            'est_resolu' => 'required|boolean'
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
            'realisation_tache_id.required' => __('validation.required', ['attribute' => __('PkgTableauBord::Remediation.realisation_tache_id')]),
            'formateur_id.required' => __('validation.required', ['attribute' => __('PkgTableauBord::Remediation.formateur_id')]),
            'type_remediation.required' => __('validation.required', ['attribute' => __('PkgTableauBord::Remediation.type_remediation')]),
            'type_remediation.max' => __('validation.type_remediationMax'),
            'diagnostic.required' => __('validation.required', ['attribute' => __('PkgTableauBord::Remediation.diagnostic')]),
            'est_resolu.required' => __('validation.required', ['attribute' => __('PkgTableauBord::Remediation.est_resolu')])
        ];
    }

}
