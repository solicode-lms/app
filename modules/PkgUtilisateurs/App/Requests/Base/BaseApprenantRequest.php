<?php
// Ce fichier est maintenu par ESSARRAJ Fouad



namespace Modules\PkgUtilisateurs\App\Requests\Base;

use Illuminate\Foundation\Http\FormRequest;

class BaseApprenantRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'nom' => 'required|max:255',
            'prenom' => 'required|max:255',
            'prenom_arab' => 'required|max:255',
            'nom_arab' => 'required|max:255',
            'tele_num' => 'required|max:255',
            'profile_image' => 'nullable|max:255',
            'matricule' => 'required|max:255',
            'sexe' => 'required|max:255',
            'actif' => 'required',
            'diplome' => 'nullable|max:255',
            'date_naissance' => 'nullable',
            'date_inscription' => 'nullable',
            'lieu_naissance' => 'nullable|max:255',
            'cin' => 'nullable|max:255',
            'adresse' => 'nullable',
            'groupe_id' => 'nullable',
            'niveaux_scolaire_id' => 'nullable',
            'nationalite_id' => 'nullable'
        ];
    }

    public function messages(): array
    {
        return [
            'nom.required' => __('validation.required', ['attribute' => __('PkgUtilisateurs::Apprenant.nom')]),
            'nom.max' => __('validation.nomMax'),
            'prenom.required' => __('validation.required', ['attribute' => __('PkgUtilisateurs::Apprenant.prenom')]),
            'prenom.max' => __('validation.prenomMax'),
            'prenom_arab.required' => __('validation.required', ['attribute' => __('PkgUtilisateurs::Apprenant.prenom_arab')]),
            'prenom_arab.max' => __('validation.prenom_arabMax'),
            'nom_arab.required' => __('validation.required', ['attribute' => __('PkgUtilisateurs::Apprenant.nom_arab')]),
            'nom_arab.max' => __('validation.nom_arabMax'),
            'tele_num.required' => __('validation.required', ['attribute' => __('PkgUtilisateurs::Apprenant.tele_num')]),
            'tele_num.max' => __('validation.tele_numMax'),
            'profile_image.required' => __('validation.required', ['attribute' => __('PkgUtilisateurs::Apprenant.profile_image')]),
            'profile_image.max' => __('validation.profile_imageMax'),
            'matricule.required' => __('validation.required', ['attribute' => __('PkgUtilisateurs::Apprenant.matricule')]),
            'matricule.max' => __('validation.matriculeMax'),
            'sexe.required' => __('validation.required', ['attribute' => __('PkgUtilisateurs::Apprenant.sexe')]),
            'sexe.max' => __('validation.sexeMax'),
            'actif.required' => __('validation.required', ['attribute' => __('PkgUtilisateurs::Apprenant.actif')]),
            'actif.max' => __('validation.actifMax'),
            'diplome.required' => __('validation.required', ['attribute' => __('PkgUtilisateurs::Apprenant.diplome')]),
            'diplome.max' => __('validation.diplomeMax'),
            'date_naissance.required' => __('validation.required', ['attribute' => __('PkgUtilisateurs::Apprenant.date_naissance')]),
            'date_naissance.max' => __('validation.date_naissanceMax'),
            'date_inscription.required' => __('validation.required', ['attribute' => __('PkgUtilisateurs::Apprenant.date_inscription')]),
            'date_inscription.max' => __('validation.date_inscriptionMax'),
            'lieu_naissance.required' => __('validation.required', ['attribute' => __('PkgUtilisateurs::Apprenant.lieu_naissance')]),
            'lieu_naissance.max' => __('validation.lieu_naissanceMax'),
            'cin.required' => __('validation.required', ['attribute' => __('PkgUtilisateurs::Apprenant.cin')]),
            'cin.max' => __('validation.cinMax'),
            'adresse.required' => __('validation.required', ['attribute' => __('PkgUtilisateurs::Apprenant.adresse')]),
            'adresse.max' => __('validation.adresseMax'),
            'groupe_id.required' => __('validation.required', ['attribute' => __('PkgUtilisateurs::Apprenant.groupe_id')]),
            'groupe_id.max' => __('validation.groupe_idMax'),
            'niveaux_scolaire_id.required' => __('validation.required', ['attribute' => __('PkgUtilisateurs::Apprenant.niveaux_scolaire_id')]),
            'niveaux_scolaire_id.max' => __('validation.niveaux_scolaire_idMax'),
            'nationalite_id.required' => __('validation.required', ['attribute' => __('PkgUtilisateurs::Apprenant.nationalite_id')]),
            'nationalite_id.max' => __('validation.nationalite_idMax')
        ];
    }
}
