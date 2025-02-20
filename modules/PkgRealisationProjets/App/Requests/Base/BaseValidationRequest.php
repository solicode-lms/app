<?php
// Ce fichier est maintenu par ESSARRAJ Fouad



namespace Modules\PkgRealisationProjets\App\Requests\Base;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Modules\PkgRealisationProjets\Models\Validation;

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
            'transfert_competence_id' => 'required',
            'note' => 'nullable',
            'message' => 'nullable|string',
            'is_valide' => 'required|boolean',
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
            'transfert_competence_id.required' => __('validation.required', ['attribute' => __('PkgRealisationProjets::Validation.transfert_competence_id')]),
            'note.required' => __('validation.required', ['attribute' => __('PkgRealisationProjets::Validation.note')]),
            'message.required' => __('validation.required', ['attribute' => __('PkgRealisationProjets::Validation.message')]),
            'is_valide.required' => __('validation.required', ['attribute' => __('PkgRealisationProjets::Validation.is_valide')]),
            'realisation_projet_id.required' => __('validation.required', ['attribute' => __('PkgRealisationProjets::Validation.realisation_projet_id')])
        ];
    }

    
}
