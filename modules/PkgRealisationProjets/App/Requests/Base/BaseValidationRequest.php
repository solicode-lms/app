<?php
// Ce fichier est maintenu par ESSARRAJ Fouad



namespace Modules\PkgRealisationProjets\App\Requests\Base;

use Illuminate\Foundation\Http\FormRequest;

class BaseValidationRequest extends FormRequest
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
            'note' => 'nullable',
            'message' => 'nullable|string|max:255',
            'is_valide' => 'required|integer',
            'transfert_competence_id' => 'required',
            'realisation_projet_id' => 'required'
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
            'note.required' => __('validation.required', ['attribute' => __('PkgRealisationProjets::Validation.note')]),
            'message.required' => __('validation.required', ['attribute' => __('PkgRealisationProjets::Validation.message')]),
            'message.max' => __('validation.messageMax'),
            'is_valide.required' => __('validation.required', ['attribute' => __('PkgRealisationProjets::Validation.is_valide')]),
            'transfert_competence_id.required' => __('validation.required', ['attribute' => __('PkgRealisationProjets::Validation.transfert_competence_id')]),
            'realisation_projet_id.required' => __('validation.required', ['attribute' => __('PkgRealisationProjets::Validation.realisation_projet_id')])
        ];
    }
}
