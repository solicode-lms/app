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
            'qcm_id' => 'required',
            'affectation_projet_id' => 'required',
            'date_affectation' => 'nullable',
            'saise_automatique_note_qcm' => 'required|boolean'
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
            'qcm_id.required' => __('validation.required', ['attribute' => __('PkgQcm::AffectationQcmProjet.qcm_id')]),
            'affectation_projet_id.required' => __('validation.required', ['attribute' => __('PkgQcm::AffectationQcmProjet.affectation_projet_id')]),
            'date_affectation.required' => __('validation.required', ['attribute' => __('PkgQcm::AffectationQcmProjet.date_affectation')]),
            'saise_automatique_note_qcm.required' => __('validation.required', ['attribute' => __('PkgQcm::AffectationQcmProjet.saise_automatique_note_qcm')])
        ];
    }

}
