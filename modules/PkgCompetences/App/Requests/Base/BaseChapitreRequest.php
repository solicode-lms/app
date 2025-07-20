<?php
// Ce fichier est maintenu par ESSARRAJ Fouad



namespace Modules\PkgCompetences\App\Requests\Base;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Modules\PkgCompetences\Models\Chapitre;

class BaseChapitreRequest extends FormRequest
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
            'code' => 'nullable|string|max:255',
            'nom' => 'required|string|max:255',
            'unite_apprentissage_id' => 'required',
            'lien' => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'duree_en_heure' => 'required',
            'isOfficiel' => 'nullable|boolean',
            'formateur_id' => 'nullable'
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
            'ordre.required' => __('validation.required', ['attribute' => __('PkgCompetences::Chapitre.ordre')]),
            'code.required' => __('validation.required', ['attribute' => __('PkgCompetences::Chapitre.code')]),
            'code.max' => __('validation.codeMax'),
            'nom.required' => __('validation.required', ['attribute' => __('PkgCompetences::Chapitre.nom')]),
            'nom.max' => __('validation.nomMax'),
            'unite_apprentissage_id.required' => __('validation.required', ['attribute' => __('PkgCompetences::Chapitre.unite_apprentissage_id')]),
            'lien.required' => __('validation.required', ['attribute' => __('PkgCompetences::Chapitre.lien')]),
            'lien.max' => __('validation.lienMax'),
            'description.required' => __('validation.required', ['attribute' => __('PkgCompetences::Chapitre.description')]),
            'duree_en_heure.required' => __('validation.required', ['attribute' => __('PkgCompetences::Chapitre.duree_en_heure')]),
            'isOfficiel.required' => __('validation.required', ['attribute' => __('PkgCompetences::Chapitre.isOfficiel')]),
            'formateur_id.required' => __('validation.required', ['attribute' => __('PkgCompetences::Chapitre.formateur_id')])
        ];
    }

    
}
