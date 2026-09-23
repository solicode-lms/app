<?php
// Ce fichier est maintenu par ESSARRAJ Fouad



namespace Modules\PkgQcm\App\Requests\Base;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Modules\PkgQcm\Models\Qcm;

class BaseQcmRequest extends FormRequest
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
            'titre' => 'required|string|max:255',
            'description' => 'nullable|string',
            'duree_minutes' => 'nullable|integer',
            'is_duree_limitee' => 'required|boolean',
            'is_publie' => 'required|boolean',
            'formateur_id' => 'nullable'
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
            'titre.required' => __('validation.required', ['attribute' => __('PkgQcm::Qcm.titre')]),
            'titre.max' => __('validation.titreMax'),
            'description.required' => __('validation.required', ['attribute' => __('PkgQcm::Qcm.description')]),
            'duree_minutes.required' => __('validation.required', ['attribute' => __('PkgQcm::Qcm.duree_minutes')]),
            'is_duree_limitee.required' => __('validation.required', ['attribute' => __('PkgQcm::Qcm.is_duree_limitee')]),
            'is_publie.required' => __('validation.required', ['attribute' => __('PkgQcm::Qcm.is_publie')]),
            'formateur_id.required' => __('validation.required', ['attribute' => __('PkgQcm::Qcm.formateur_id')])
        ];
    }

}
