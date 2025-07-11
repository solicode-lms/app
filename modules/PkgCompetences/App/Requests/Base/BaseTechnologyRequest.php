<?php
// Ce fichier est maintenu par ESSARRAJ Fouad



namespace Modules\PkgCompetences\App\Requests\Base;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Modules\PkgCompetences\Models\Technology;

class BaseTechnologyRequest extends FormRequest
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
            'category_technology_id' => 'required',
            'competences' => 'nullable|array',
            'transfertCompetences' => 'nullable|array',
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
            'nom.required' => __('validation.required', ['attribute' => __('PkgCompetences::Technology.nom')]),
            'nom.max' => __('validation.nomMax'),
            'category_technology_id.required' => __('validation.required', ['attribute' => __('PkgCompetences::Technology.category_technology_id')]),
            'competences.required' => __('validation.required', ['attribute' => __('PkgCompetences::Technology.competences')]),
            'competences.array' => __('validation.array', ['attribute' => __('PkgCompetences::Technology.competences')]),
            'transfertCompetences.required' => __('validation.required', ['attribute' => __('PkgCompetences::Technology.transfertCompetences')]),
            'transfertCompetences.array' => __('validation.array', ['attribute' => __('PkgCompetences::Technology.transfertCompetences')]),
            'description.required' => __('validation.required', ['attribute' => __('PkgCompetences::Technology.description')])
        ];
    }

    
}
