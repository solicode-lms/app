<?php
// Ce fichier est maintenu par ESSARRAJ Fouad



namespace Modules\PkgQcm\App\Requests\Base;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Modules\PkgQcm\Models\AffectationQcmProjet;

class BaseAffectationQcmProjetRequest extends FormRequest
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
            'affectation_projet_id' => 'required',
            'qcm_id' => 'required'
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
            'affectation_projet_id.required' => __('validation.required', ['attribute' => __('PkgQcm::AffectationQcmProjet.affectation_projet_id')]),
            'qcm_id.required' => __('validation.required', ['attribute' => __('PkgQcm::AffectationQcmProjet.qcm_id')])
        ];
    }

}
