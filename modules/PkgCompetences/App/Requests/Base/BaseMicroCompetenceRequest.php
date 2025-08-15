<?php
// Ce fichier est maintenu par ESSARRAJ Fouad



namespace Modules\PkgCompetences\App\Requests\Base;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Modules\PkgCompetences\Models\MicroCompetence;

class BaseMicroCompetenceRequest extends FormRequest
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
            'ordre' => 'required|integer',
            'code' => 'required|string|max:255',
            'titre' => 'required|string|max:255',
            'sous_titre' => 'nullable|string|max:255',
            'competence_id' => 'nullable',
            'lien' => 'nullable|string|max:255|url',
            'description' => 'nullable|string'
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
            'ordre.required' => __('validation.required', ['attribute' => __('PkgCompetences::MicroCompetence.ordre')]),
            'code.required' => __('validation.required', ['attribute' => __('PkgCompetences::MicroCompetence.code')]),
            'code.max' => __('validation.codeMax'),
            'titre.required' => __('validation.required', ['attribute' => __('PkgCompetences::MicroCompetence.titre')]),
            'titre.max' => __('validation.titreMax'),
            'sous_titre.required' => __('validation.required', ['attribute' => __('PkgCompetences::MicroCompetence.sous_titre')]),
            'sous_titre.max' => __('validation.sous_titreMax'),
            'competence_id.required' => __('validation.required', ['attribute' => __('PkgCompetences::MicroCompetence.competence_id')]),
            'lien.required' => __('validation.required', ['attribute' => __('PkgCompetences::MicroCompetence.lien')]),
            'lien.max' => __('validation.lienMax'),
            'description.required' => __('validation.required', ['attribute' => __('PkgCompetences::MicroCompetence.description')])
        ];
    }

}
