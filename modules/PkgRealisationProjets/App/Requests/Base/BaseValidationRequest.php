<?php
// Ce fichier est maintenu par ESSARRAJ Fouad



namespace Modules\PkgRealisationProjets\App\Requests\Base;

use Illuminate\Foundation\Http\FormRequest;

class BaseValidationRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'note' => 'nullable',
            'message' => 'nullable|max:255',
            'is_valide' => 'required',
            'transfert_competence_id' => 'required',
            'realisation_projet_id' => 'required'
        ];
    }

    public function messages(): array
    {
        return [
            'note.required' => __('validation.required', ['attribute' => __('PkgRealisationProjets::Validation.note')]),
            'note.max' => __('validation.noteMax'),
            'message.required' => __('validation.required', ['attribute' => __('PkgRealisationProjets::Validation.message')]),
            'message.max' => __('validation.messageMax'),
            'is_valide.required' => __('validation.required', ['attribute' => __('PkgRealisationProjets::Validation.is_valide')]),
            'is_valide.max' => __('validation.is_valideMax'),
            'transfert_competence_id.required' => __('validation.required', ['attribute' => __('PkgRealisationProjets::Validation.transfert_competence_id')]),
            'transfert_competence_id.max' => __('validation.transfert_competence_idMax'),
            'realisation_projet_id.required' => __('validation.required', ['attribute' => __('PkgRealisationProjets::Validation.realisation_projet_id')]),
            'realisation_projet_id.max' => __('validation.realisation_projet_idMax')
        ];
    }
}
