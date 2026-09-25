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
            'affectation_qcm_projet_id' => 'nullable',
            'qcm_id' => 'nullable',
            'apprenant_id' => 'required',
            'etat_realisation_qcm_id' => 'nullable',
            'date_debut' => 'nullable',
            'date_fin' => 'nullable',
            'date_soumission' => 'nullable',
            'date_validation' => 'nullable',
            'note_obtenu' => 'nullable',
            'statut' => 'nullable|string|max:255'
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
            'affectation_qcm_projet_id.required' => __('validation.required', ['attribute' => __('PkgQcm::RealisationQcm.affectation_qcm_projet_id')]),
            'qcm_id.required' => __('validation.required', ['attribute' => __('PkgQcm::RealisationQcm.qcm_id')]),
            'apprenant_id.required' => __('validation.required', ['attribute' => __('PkgQcm::RealisationQcm.apprenant_id')]),
            'etat_realisation_qcm_id.required' => __('validation.required', ['attribute' => __('PkgQcm::RealisationQcm.etat_realisation_qcm_id')]),
            'date_debut.required' => __('validation.required', ['attribute' => __('PkgQcm::RealisationQcm.date_debut')]),
            'date_fin.required' => __('validation.required', ['attribute' => __('PkgQcm::RealisationQcm.date_fin')]),
            'date_soumission.required' => __('validation.required', ['attribute' => __('PkgQcm::RealisationQcm.date_soumission')]),
            'date_validation.required' => __('validation.required', ['attribute' => __('PkgQcm::RealisationQcm.date_validation')]),
            'note_obtenu.required' => __('validation.required', ['attribute' => __('PkgQcm::RealisationQcm.note_obtenu')]),
            'statut.required' => __('validation.required', ['attribute' => __('PkgQcm::RealisationQcm.statut')]),
            'statut.max' => __('validation.statutMax')
        ];
    }

}
