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
            'actif' => 'required',
            'adresse' => 'nullable',
            'cin' => 'nullable|max:255',
            'date_inscription' => 'nullable',
            'date_naissance' => 'nullable',
            'diplome' => 'nullable|max:255',
            'groupe_id' => 'nullable',
            'lieu_naissance' => 'nullable|max:255',
            'matricule' => 'required|max:255',
            'nationalite_id' => 'nullable',
            'niveaux_scolaire_id' => 'nullable',
            'nom' => 'required|max:255',
            'nom_arab' => 'required|max:255',
            'prenom' => 'required|max:255',
            'prenom_arab' => 'required|max:255',
            'profile_image' => 'nullable|max:255',
            'sexe' => 'required|max:255',
            'tele_num' => 'required|max:255'
        ];
    }

    public function messages(): array
    {
        return [
            'actif.required' => __('validation.required', ['attribute' => __('PkgUtilisateurs::Apprenant.actif')]),
            'actif.max' => __('validation.actifMax'),
            'adresse.required' => __('validation.required', ['attribute' => __('PkgUtilisateurs::Apprenant.adresse')]),
            'adresse.max' => __('validation.adresseMax'),
            'cin.required' => __('validation.required', ['attribute' => __('PkgUtilisateurs::Apprenant.cin')]),
            'cin.max' => __('validation.cinMax'),
            'date_inscription.required' => __('validation.required', ['attribute' => __('PkgUtilisateurs::Apprenant.date_inscription')]),
            'date_inscription.max' => __('validation.date_inscriptionMax'),
            'date_naissance.required' => __('validation.required', ['attribute' => __('PkgUtilisateurs::Apprenant.date_naissance')]),
            'date_naissance.max' => __('validation.date_naissanceMax'),
            'diplome.required' => __('validation.required', ['attribute' => __('PkgUtilisateurs::Apprenant.diplome')]),
            'diplome.max' => __('validation.diplomeMax'),
            'groupe_id.required' => __('validation.required', ['attribute' => __('PkgUtilisateurs::Apprenant.groupe_id')]),
            'groupe_id.max' => __('validation.groupe_idMax'),
            'lieu_naissance.required' => __('validation.required', ['attribute' => __('PkgUtilisateurs::Apprenant.lieu_naissance')]),
            'lieu_naissance.max' => __('validation.lieu_naissanceMax'),
            'matricule.required' => __('validation.required', ['attribute' => __('PkgUtilisateurs::Apprenant.matricule')]),
            'matricule.max' => __('validation.matriculeMax'),
            'nationalite_id.required' => __('validation.required', ['attribute' => __('PkgUtilisateurs::Apprenant.nationalite_id')]),
            'nationalite_id.max' => __('validation.nationalite_idMax'),
            'niveaux_scolaire_id.required' => __('validation.required', ['attribute' => __('PkgUtilisateurs::Apprenant.niveaux_scolaire_id')]),
            'niveaux_scolaire_id.max' => __('validation.niveaux_scolaire_idMax'),
            'nom.required' => __('validation.required', ['attribute' => __('PkgUtilisateurs::Apprenant.nom')]),
            'nom.max' => __('validation.nomMax'),
            'nom_arab.required' => __('validation.required', ['attribute' => __('PkgUtilisateurs::Apprenant.nom_arab')]),
            'nom_arab.max' => __('validation.nom_arabMax'),
            'prenom.required' => __('validation.required', ['attribute' => __('PkgUtilisateurs::Apprenant.prenom')]),
            'prenom.max' => __('validation.prenomMax'),
            'prenom_arab.required' => __('validation.required', ['attribute' => __('PkgUtilisateurs::Apprenant.prenom_arab')]),
            'prenom_arab.max' => __('validation.prenom_arabMax'),
            'profile_image.required' => __('validation.required', ['attribute' => __('PkgUtilisateurs::Apprenant.profile_image')]),
            'profile_image.max' => __('validation.profile_imageMax'),
            'sexe.required' => __('validation.required', ['attribute' => __('PkgUtilisateurs::Apprenant.sexe')]),
            'sexe.max' => __('validation.sexeMax'),
            'tele_num.required' => __('validation.required', ['attribute' => __('PkgUtilisateurs::Apprenant.tele_num')]),
            'tele_num.max' => __('validation.tele_numMax')
        ];
    }
}
