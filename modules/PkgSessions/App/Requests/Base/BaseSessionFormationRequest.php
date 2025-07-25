<?php
// Ce fichier est maintenu par ESSARRAJ Fouad



namespace Modules\PkgSessions\App\Requests\Base;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Modules\PkgSessions\Models\SessionFormation;

class BaseSessionFormationRequest extends FormRequest
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
            'thematique' => 'nullable|string|max:255',
            'filiere_id' => 'nullable',
            'objectifs_pedagogique' => 'required|string',
            'titre_prototype' => 'required|string|max:255',
            'description_prototype' => 'required|string',
            'contraintes_prototype' => 'nullable|string',
            'titre_projet' => 'required|string|max:255',
            'description_projet' => 'required|string',
            'contraintes_projet' => 'nullable|string',
            'remarques' => 'nullable|string',
            'date_debut' => 'nullable',
            'date_fin' => 'nullable',
            'jour_feries_vacances' => 'nullable|string',
            'annee_formation_id' => 'nullable'
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
            'ordre.required' => __('validation.required', ['attribute' => __('PkgSessions::SessionFormation.ordre')]),
            'titre.required' => __('validation.required', ['attribute' => __('PkgSessions::SessionFormation.titre')]),
            'titre.max' => __('validation.titreMax'),
            'thematique.required' => __('validation.required', ['attribute' => __('PkgSessions::SessionFormation.thematique')]),
            'thematique.max' => __('validation.thematiqueMax'),
            'filiere_id.required' => __('validation.required', ['attribute' => __('PkgSessions::SessionFormation.filiere_id')]),
            'objectifs_pedagogique.required' => __('validation.required', ['attribute' => __('PkgSessions::SessionFormation.objectifs_pedagogique')]),
            'titre_prototype.required' => __('validation.required', ['attribute' => __('PkgSessions::SessionFormation.titre_prototype')]),
            'titre_prototype.max' => __('validation.titre_prototypeMax'),
            'description_prototype.required' => __('validation.required', ['attribute' => __('PkgSessions::SessionFormation.description_prototype')]),
            'contraintes_prototype.required' => __('validation.required', ['attribute' => __('PkgSessions::SessionFormation.contraintes_prototype')]),
            'titre_projet.required' => __('validation.required', ['attribute' => __('PkgSessions::SessionFormation.titre_projet')]),
            'titre_projet.max' => __('validation.titre_projetMax'),
            'description_projet.required' => __('validation.required', ['attribute' => __('PkgSessions::SessionFormation.description_projet')]),
            'contraintes_projet.required' => __('validation.required', ['attribute' => __('PkgSessions::SessionFormation.contraintes_projet')]),
            'remarques.required' => __('validation.required', ['attribute' => __('PkgSessions::SessionFormation.remarques')]),
            'date_debut.required' => __('validation.required', ['attribute' => __('PkgSessions::SessionFormation.date_debut')]),
            'date_fin.required' => __('validation.required', ['attribute' => __('PkgSessions::SessionFormation.date_fin')]),
            'jour_feries_vacances.required' => __('validation.required', ['attribute' => __('PkgSessions::SessionFormation.jour_feries_vacances')]),
            'annee_formation_id.required' => __('validation.required', ['attribute' => __('PkgSessions::SessionFormation.annee_formation_id')])
        ];
    }

    
}
