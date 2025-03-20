<?php
// Ce fichier est maintenu par ESSARRAJ Fouad



namespace Modules\PkgAutoformation\App\Requests\Base;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Modules\PkgAutoformation\Models\Chapitre;

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
            'nom' => 'required|string|max:255',
            'lien' => 'nullable|string|max:255',
            'coefficient' => 'required|integer',
            'description' => 'nullable|string',
            'ordre' => 'required|integer',
            'is_officiel' => 'required|boolean',
            'formation_id' => 'required',
            'niveau_competence_id' => 'nullable',
            'formateur_id' => 'nullable',
            'chapitre_officiel_id' => 'nullable'
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
            'nom.required' => __('validation.required', ['attribute' => __('PkgAutoformation::Chapitre.nom')]),
            'nom.max' => __('validation.nomMax'),
            'lien.required' => __('validation.required', ['attribute' => __('PkgAutoformation::Chapitre.lien')]),
            'lien.max' => __('validation.lienMax'),
            'coefficient.required' => __('validation.required', ['attribute' => __('PkgAutoformation::Chapitre.coefficient')]),
            'description.required' => __('validation.required', ['attribute' => __('PkgAutoformation::Chapitre.description')]),
            'ordre.required' => __('validation.required', ['attribute' => __('PkgAutoformation::Chapitre.ordre')]),
            'is_officiel.required' => __('validation.required', ['attribute' => __('PkgAutoformation::Chapitre.is_officiel')]),
            'formation_id.required' => __('validation.required', ['attribute' => __('PkgAutoformation::Chapitre.formation_id')]),
            'niveau_competence_id.required' => __('validation.required', ['attribute' => __('PkgAutoformation::Chapitre.niveau_competence_id')]),
            'formateur_id.required' => __('validation.required', ['attribute' => __('PkgAutoformation::Chapitre.formateur_id')]),
            'chapitre_officiel_id.required' => __('validation.required', ['attribute' => __('PkgAutoformation::Chapitre.chapitre_officiel_id')])
        ];
    }

    
}
