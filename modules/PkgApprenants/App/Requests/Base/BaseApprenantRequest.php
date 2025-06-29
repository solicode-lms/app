<?php
// Ce fichier est maintenu par ESSARRAJ Fouad



namespace Modules\PkgApprenants\App\Requests\Base;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Modules\PkgApprenants\Models\Apprenant;

class BaseApprenantRequest extends FormRequest
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
            'nom_arab' => 'required|string|max:255',
            'prenom' => 'required|string|max:255',
            'prenom_arab' => 'required|string|max:255',
            'profile_image' => 'nullable|string|max:255',
            'cin' => 'nullable|string|max:255',
            'date_naissance' => 'nullable',
            'sexe' => 'required|string|max:255',
            'nationalite_id' => 'nullable',
            'lieu_naissance' => 'nullable|string|max:255',
            'diplome' => 'nullable|string|max:255',
            'adresse' => 'nullable|string',
            'niveaux_scolaire_id' => 'nullable',
            'tele_num' => 'required|string|max:255',
            'user_id' => 'nullable',
            'sousGroupes' => 'nullable|array',
            'matricule' => 'required|string|max:255',
            'groupes' => 'nullable|array',
            'date_inscription' => 'nullable',
            'actif' => 'nullable|boolean'
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
            'nom.required' => __('validation.required', ['attribute' => __('PkgApprenants::Apprenant.nom')]),
            'nom.max' => __('validation.nomMax'),
            'nom_arab.required' => __('validation.required', ['attribute' => __('PkgApprenants::Apprenant.nom_arab')]),
            'nom_arab.max' => __('validation.nom_arabMax'),
            'prenom.required' => __('validation.required', ['attribute' => __('PkgApprenants::Apprenant.prenom')]),
            'prenom.max' => __('validation.prenomMax'),
            'prenom_arab.required' => __('validation.required', ['attribute' => __('PkgApprenants::Apprenant.prenom_arab')]),
            'prenom_arab.max' => __('validation.prenom_arabMax'),
            'profile_image.required' => __('validation.required', ['attribute' => __('PkgApprenants::Apprenant.profile_image')]),
            'profile_image.max' => __('validation.profile_imageMax'),
            'cin.required' => __('validation.required', ['attribute' => __('PkgApprenants::Apprenant.cin')]),
            'cin.max' => __('validation.cinMax'),
            'date_naissance.required' => __('validation.required', ['attribute' => __('PkgApprenants::Apprenant.date_naissance')]),
            'sexe.required' => __('validation.required', ['attribute' => __('PkgApprenants::Apprenant.sexe')]),
            'sexe.max' => __('validation.sexeMax'),
            'nationalite_id.required' => __('validation.required', ['attribute' => __('PkgApprenants::Apprenant.nationalite_id')]),
            'lieu_naissance.required' => __('validation.required', ['attribute' => __('PkgApprenants::Apprenant.lieu_naissance')]),
            'lieu_naissance.max' => __('validation.lieu_naissanceMax'),
            'diplome.required' => __('validation.required', ['attribute' => __('PkgApprenants::Apprenant.diplome')]),
            'diplome.max' => __('validation.diplomeMax'),
            'adresse.required' => __('validation.required', ['attribute' => __('PkgApprenants::Apprenant.adresse')]),
            'niveaux_scolaire_id.required' => __('validation.required', ['attribute' => __('PkgApprenants::Apprenant.niveaux_scolaire_id')]),
            'tele_num.required' => __('validation.required', ['attribute' => __('PkgApprenants::Apprenant.tele_num')]),
            'tele_num.max' => __('validation.tele_numMax'),
            'user_id.required' => __('validation.required', ['attribute' => __('PkgApprenants::Apprenant.user_id')]),
            'sousGroupes.required' => __('validation.required', ['attribute' => __('PkgApprenants::Apprenant.sousGroupes')]),
            'sousGroupes.array' => __('validation.array', ['attribute' => __('PkgApprenants::Apprenant.sousGroupes')]),
            'matricule.required' => __('validation.required', ['attribute' => __('PkgApprenants::Apprenant.matricule')]),
            'matricule.max' => __('validation.matriculeMax'),
            'groupes.required' => __('validation.required', ['attribute' => __('PkgApprenants::Apprenant.groupes')]),
            'groupes.array' => __('validation.array', ['attribute' => __('PkgApprenants::Apprenant.groupes')]),
            'date_inscription.required' => __('validation.required', ['attribute' => __('PkgApprenants::Apprenant.date_inscription')]),
            'actif.required' => __('validation.required', ['attribute' => __('PkgApprenants::Apprenant.actif')])
        ];
    }

    
}
