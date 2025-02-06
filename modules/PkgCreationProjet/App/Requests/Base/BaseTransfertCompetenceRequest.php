<?php
// Ce fichier est maintenu par ESSARRAJ Fouad



namespace Modules\PkgCreationProjet\App\Requests\Base;

use Illuminate\Foundation\Http\FormRequest;

class BaseTransfertCompetenceRequest extends FormRequest
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
            'competence_id' => 'required',
            'niveau_difficulte_id' => 'required',
            'question' => 'nullable|string',
            'technologies' => 'nullable|array',
            'note' => 'nullable',
            'projet_id' => 'required'
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
            'competence_id.required' => __('validation.required', ['attribute' => __('PkgCreationProjet::TransfertCompetence.competence_id')]),
            'niveau_difficulte_id.required' => __('validation.required', ['attribute' => __('PkgCreationProjet::TransfertCompetence.niveau_difficulte_id')]),
            'question.required' => __('validation.required', ['attribute' => __('PkgCreationProjet::TransfertCompetence.question')]),
            'technologies.required' => __('validation.required', ['attribute' => __('PkgCreationProjet::TransfertCompetence.technologies')]),
            'technologies.array' => __('validation.array', ['attribute' => __('PkgCreationProjet::TransfertCompetence.technologies')]),
            'note.required' => __('validation.required', ['attribute' => __('PkgCreationProjet::TransfertCompetence.note')]),
            'projet_id.required' => __('validation.required', ['attribute' => __('PkgCreationProjet::TransfertCompetence.projet_id')])
        ];
    }
}
