<?php
// Ce fichier est maintenu par ESSARRAJ Fouad



namespace Modules\PkgUtilisateurs\App\Requests\Base;

use Illuminate\Foundation\Http\FormRequest;

class BaseApprenantKonosyRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'Adresse' => 'nullable',
            'CIN' => 'nullable|max:255',
            'CodeDiplome' => 'nullable|max:255',
            'DateInscription' => 'nullable|max:255',
            'DateNaissance' => 'nullable|max:255',
            'Diplome' => 'nullable|max:255',
            'EtudiantActif' => 'required|max:255',
            'LibelleLong' => 'nullable|max:255',
            'LieuNaissance' => 'nullable|max:255',
            'MatriculeEtudiant' => 'required|max:255',
            'Nationalite' => 'nullable|max:255',
            'NiveauScolaire' => 'nullable|max:255',
            'Nom' => 'required|max:255',
            'Nom_Arabe' => 'nullable|max:255',
            'NTelephone' => 'nullable|max:255',
            'Prenom' => 'required|max:255',
            'Prenom_Arabe' => 'nullable|max:255',
            'Principale' => 'nullable|max:255',
            'Sexe' => 'required|max:255'
        ];
    }

    public function messages(): array
    {
        return [
            'Adresse.required' => __('validation.required', ['attribute' => __('PkgUtilisateurs::ApprenantKonosy.Adresse')]),
            'Adresse.max' => __('validation.AdresseMax'),
            'CIN.required' => __('validation.required', ['attribute' => __('PkgUtilisateurs::ApprenantKonosy.CIN')]),
            'CIN.max' => __('validation.CINMax'),
            'CodeDiplome.required' => __('validation.required', ['attribute' => __('PkgUtilisateurs::ApprenantKonosy.CodeDiplome')]),
            'CodeDiplome.max' => __('validation.CodeDiplomeMax'),
            'DateInscription.required' => __('validation.required', ['attribute' => __('PkgUtilisateurs::ApprenantKonosy.DateInscription')]),
            'DateInscription.max' => __('validation.DateInscriptionMax'),
            'DateNaissance.required' => __('validation.required', ['attribute' => __('PkgUtilisateurs::ApprenantKonosy.DateNaissance')]),
            'DateNaissance.max' => __('validation.DateNaissanceMax'),
            'Diplome.required' => __('validation.required', ['attribute' => __('PkgUtilisateurs::ApprenantKonosy.Diplome')]),
            'Diplome.max' => __('validation.DiplomeMax'),
            'EtudiantActif.required' => __('validation.required', ['attribute' => __('PkgUtilisateurs::ApprenantKonosy.EtudiantActif')]),
            'EtudiantActif.max' => __('validation.EtudiantActifMax'),
            'LibelleLong.required' => __('validation.required', ['attribute' => __('PkgUtilisateurs::ApprenantKonosy.LibelleLong')]),
            'LibelleLong.max' => __('validation.LibelleLongMax'),
            'LieuNaissance.required' => __('validation.required', ['attribute' => __('PkgUtilisateurs::ApprenantKonosy.LieuNaissance')]),
            'LieuNaissance.max' => __('validation.LieuNaissanceMax'),
            'MatriculeEtudiant.required' => __('validation.required', ['attribute' => __('PkgUtilisateurs::ApprenantKonosy.MatriculeEtudiant')]),
            'MatriculeEtudiant.max' => __('validation.MatriculeEtudiantMax'),
            'Nationalite.required' => __('validation.required', ['attribute' => __('PkgUtilisateurs::ApprenantKonosy.Nationalite')]),
            'Nationalite.max' => __('validation.NationaliteMax'),
            'NiveauScolaire.required' => __('validation.required', ['attribute' => __('PkgUtilisateurs::ApprenantKonosy.NiveauScolaire')]),
            'NiveauScolaire.max' => __('validation.NiveauScolaireMax'),
            'Nom.required' => __('validation.required', ['attribute' => __('PkgUtilisateurs::ApprenantKonosy.Nom')]),
            'Nom.max' => __('validation.NomMax'),
            'Nom_Arabe.required' => __('validation.required', ['attribute' => __('PkgUtilisateurs::ApprenantKonosy.Nom_Arabe')]),
            'Nom_Arabe.max' => __('validation.Nom_ArabeMax'),
            'NTelephone.required' => __('validation.required', ['attribute' => __('PkgUtilisateurs::ApprenantKonosy.NTelephone')]),
            'NTelephone.max' => __('validation.NTelephoneMax'),
            'Prenom.required' => __('validation.required', ['attribute' => __('PkgUtilisateurs::ApprenantKonosy.Prenom')]),
            'Prenom.max' => __('validation.PrenomMax'),
            'Prenom_Arabe.required' => __('validation.required', ['attribute' => __('PkgUtilisateurs::ApprenantKonosy.Prenom_Arabe')]),
            'Prenom_Arabe.max' => __('validation.Prenom_ArabeMax'),
            'Principale.required' => __('validation.required', ['attribute' => __('PkgUtilisateurs::ApprenantKonosy.Principale')]),
            'Principale.max' => __('validation.PrincipaleMax'),
            'Sexe.required' => __('validation.required', ['attribute' => __('PkgUtilisateurs::ApprenantKonosy.Sexe')]),
            'Sexe.max' => __('validation.SexeMax')
        ];
    }
}
