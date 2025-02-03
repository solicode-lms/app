<?php
// Ce fichier est maintenu par ESSARRAJ Fouad



namespace Modules\PkgCreationProjet\App\Requests\Base;

use Illuminate\Foundation\Http\FormRequest;

class BaseTransfertCompetenceRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'note' => 'nullable',
            'question' => 'nullable',
            'projet_id' => 'required',
            'competence_id' => 'required',
            'niveau_difficulte_id' => 'required'
        ];
    }

    public function messages(): array
    {
        return [
            'note.required' => __('validation.required', ['attribute' => __('PkgCreationProjet::TransfertCompetence.note')]),
            'note.max' => __('validation.noteMax'),
            'question.required' => __('validation.required', ['attribute' => __('PkgCreationProjet::TransfertCompetence.question')]),
            'question.max' => __('validation.questionMax'),
            'projet_id.required' => __('validation.required', ['attribute' => __('PkgCreationProjet::TransfertCompetence.projet_id')]),
            'projet_id.max' => __('validation.projet_idMax'),
            'competence_id.required' => __('validation.required', ['attribute' => __('PkgCreationProjet::TransfertCompetence.competence_id')]),
            'competence_id.max' => __('validation.competence_idMax'),
            'niveau_difficulte_id.required' => __('validation.required', ['attribute' => __('PkgCreationProjet::TransfertCompetence.niveau_difficulte_id')]),
            'niveau_difficulte_id.max' => __('validation.niveau_difficulte_idMax')
        ];
    }
}
