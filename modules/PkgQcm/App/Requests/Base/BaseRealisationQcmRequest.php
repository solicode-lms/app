<?php
// Ce fichier est maintenu par ESSARRAJ Fouad



namespace Modules\PkgQcm\App\Requests\Base;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Modules\PkgQcm\Models\RealisationQcm;

class BaseRealisationQcmRequest extends FormRequest
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
            'date_debut' => 'nullable',
            'date_fin' => 'nullable',
            'date_soumission' => 'nullable',
            'statut' => 'nullable|string|max:255',
            'date_validation' => 'nullable',
            'affectation_qcm_projet_id' => 'required',
            'etat_realisation_qcm_id' => 'nullable',
            'qcm_id' => 'required',
            'apprenant_id' => 'required'
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
            'date_debut.required' => __('validation.required', ['attribute' => __('PkgQcm::RealisationQcm.date_debut')]),
            'date_fin.required' => __('validation.required', ['attribute' => __('PkgQcm::RealisationQcm.date_fin')]),
            'date_soumission.required' => __('validation.required', ['attribute' => __('PkgQcm::RealisationQcm.date_soumission')]),
            'statut.required' => __('validation.required', ['attribute' => __('PkgQcm::RealisationQcm.statut')]),
            'statut.max' => __('validation.statutMax'),
            'date_validation.required' => __('validation.required', ['attribute' => __('PkgQcm::RealisationQcm.date_validation')]),
            'affectation_qcm_projet_id.required' => __('validation.required', ['attribute' => __('PkgQcm::RealisationQcm.affectation_qcm_projet_id')]),
            'etat_realisation_qcm_id.required' => __('validation.required', ['attribute' => __('PkgQcm::RealisationQcm.etat_realisation_qcm_id')]),
            'qcm_id.required' => __('validation.required', ['attribute' => __('PkgQcm::RealisationQcm.qcm_id')]),
            'apprenant_id.required' => __('validation.required', ['attribute' => __('PkgQcm::RealisationQcm.apprenant_id')])
        ];
    }

}
