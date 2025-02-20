<?php
// Ce fichier est maintenu par ESSARRAJ Fouad



namespace Modules\PkgCompetences\App\Requests\Base;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Modules\PkgCompetences\Models\NiveauDifficulte;

class BaseNiveauDifficulteRequest extends FormRequest
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
            'nom' => 'required|string|max:255',
            'noteMin' => 'required',
            'noteMax' => 'required',
            'formateur_id' => 'required',
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
            'nom.required' => __('validation.required', ['attribute' => __('PkgCompetences::NiveauDifficulte.nom')]),
            'nom.max' => __('validation.nomMax'),
            'noteMin.required' => __('validation.required', ['attribute' => __('PkgCompetences::NiveauDifficulte.noteMin')]),
            'noteMax.required' => __('validation.required', ['attribute' => __('PkgCompetences::NiveauDifficulte.noteMax')]),
            'formateur_id.required' => __('validation.required', ['attribute' => __('PkgCompetences::NiveauDifficulte.formateur_id')]),
            'description.required' => __('validation.required', ['attribute' => __('PkgCompetences::NiveauDifficulte.description')])
        ];
    }

    
}
