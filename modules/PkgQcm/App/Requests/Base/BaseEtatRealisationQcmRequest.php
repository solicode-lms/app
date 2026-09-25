<?php
// Ce fichier est maintenu par ESSARRAJ Fouad



namespace Modules\PkgQcm\App\Requests\Base;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Modules\PkgQcm\Models\EtatRealisationQcm;

class BaseEtatRealisationQcmRequest extends FormRequest
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
            'description' => 'nullable|string|max:255',
            'is_editable_by_formateur' => 'nullable|boolean',
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
            'titre.required' => __('validation.required', ['attribute' => __('PkgQcm::EtatRealisationQcm.titre')]),
            'titre.max' => __('validation.titreMax'),
            'description.required' => __('validation.required', ['attribute' => __('PkgQcm::EtatRealisationQcm.description')]),
            'description.max' => __('validation.descriptionMax'),
            'is_editable_by_formateur.required' => __('validation.required', ['attribute' => __('PkgQcm::EtatRealisationQcm.is_editable_by_formateur')]),
            'sys_color_id.required' => __('validation.required', ['attribute' => __('PkgQcm::EtatRealisationQcm.sys_color_id')])
        ];
    }

}
