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
            'titre' => 'required|string|max:255',
            'sous_titre' => 'nullable|string|max:255',
            'code' => 'required|string|max:255',
            'lien' => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'competence_id' => 'nullable'
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
            'titre.required' => __('validation.required', ['attribute' => __('PkgCompetences::MicroCompetence.titre')]),
            'titre.max' => __('validation.titreMax'),
            'sous_titre.required' => __('validation.required', ['attribute' => __('PkgCompetences::MicroCompetence.sous_titre')]),
            'sous_titre.max' => __('validation.sous_titreMax'),
            'code.required' => __('validation.required', ['attribute' => __('PkgCompetences::MicroCompetence.code')]),
            'code.max' => __('validation.codeMax'),
            'lien.required' => __('validation.required', ['attribute' => __('PkgCompetences::MicroCompetence.lien')]),
            'lien.max' => __('validation.lienMax'),
            'description.required' => __('validation.required', ['attribute' => __('PkgCompetences::MicroCompetence.description')]),
            'competence_id.required' => __('validation.required', ['attribute' => __('PkgCompetences::MicroCompetence.competence_id')])
        ];
    }

    
}
