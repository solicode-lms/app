<?php
// Ce fichier est maintenu par ESSARRAJ Fouad



namespace Modules\PkgApprenants\App\Requests\Base;

use Illuminate\Foundation\Http\FormRequest;

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
            'prenom' => 'required|string|max:255',
            'prenom_arab' => 'required|string|max:255',
            'nom_arab' => 'required|string|max:255',
            'tele_num' => 'required|string|max:255',
            'profile_image' => 'nullable|string|max:255',
            'matricule' => 'required|string|max:255',
            'sexe' => 'required|string|max:255',
            'actif' => 'required|boolean',
            'diplome' => 'nullable|string|max:255',
            'date_naissance' => 'nullable',
            'date_inscription' => 'nullable',
            'lieu_naissance' => 'nullable|string|max:255',
            'cin' => 'nullable|string|max:255',
            'adresse' => 'nullable|string',
            'niveaux_scolaire_id' => 'nullable',
            'nationalite_id' => 'nullable',
            'user_id' => 'nullable',
            'groupes' => 'nullable|array'
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
            'prenom.required' => __('validation.required', ['attribute' => __('PkgApprenants::Apprenant.prenom')]),
            'prenom.max' => __('validation.prenomMax'),
            'prenom_arab.required' => __('validation.required', ['attribute' => __('PkgApprenants::Apprenant.prenom_arab')]),
            'prenom_arab.max' => __('validation.prenom_arabMax'),
            'nom_arab.required' => __('validation.required', ['attribute' => __('PkgApprenants::Apprenant.nom_arab')]),
            'nom_arab.max' => __('validation.nom_arabMax'),
            'tele_num.required' => __('validation.required', ['attribute' => __('PkgApprenants::Apprenant.tele_num')]),
            'tele_num.max' => __('validation.tele_numMax'),
            'profile_image.required' => __('validation.required', ['attribute' => __('PkgApprenants::Apprenant.profile_image')]),
            'profile_image.max' => __('validation.profile_imageMax'),
            'matricule.required' => __('validation.required', ['attribute' => __('PkgApprenants::Apprenant.matricule')]),
            'matricule.max' => __('validation.matriculeMax'),
            'sexe.required' => __('validation.required', ['attribute' => __('PkgApprenants::Apprenant.sexe')]),
            'sexe.max' => __('validation.sexeMax'),
            'actif.required' => __('validation.required', ['attribute' => __('PkgApprenants::Apprenant.actif')]),
            'diplome.required' => __('validation.required', ['attribute' => __('PkgApprenants::Apprenant.diplome')]),
            'diplome.max' => __('validation.diplomeMax'),
            'date_naissance.required' => __('validation.required', ['attribute' => __('PkgApprenants::Apprenant.date_naissance')]),
            'date_inscription.required' => __('validation.required', ['attribute' => __('PkgApprenants::Apprenant.date_inscription')]),
            'lieu_naissance.required' => __('validation.required', ['attribute' => __('PkgApprenants::Apprenant.lieu_naissance')]),
            'lieu_naissance.max' => __('validation.lieu_naissanceMax'),
            'cin.required' => __('validation.required', ['attribute' => __('PkgApprenants::Apprenant.cin')]),
            'cin.max' => __('validation.cinMax'),
            'adresse.required' => __('validation.required', ['attribute' => __('PkgApprenants::Apprenant.adresse')]),
            'niveaux_scolaire_id.required' => __('validation.required', ['attribute' => __('PkgApprenants::Apprenant.niveaux_scolaire_id')]),
            'nationalite_id.required' => __('validation.required', ['attribute' => __('PkgApprenants::Apprenant.nationalite_id')]),
            'user_id.required' => __('validation.required', ['attribute' => __('PkgApprenants::Apprenant.user_id')]),
            'groupes.required' => __('validation.required', ['attribute' => __('PkgApprenants::Apprenant.groupes')]),
            'groupes.array' => __('validation.array', ['attribute' => __('PkgApprenants::Apprenant.groupes')])
        ];
    }
}
