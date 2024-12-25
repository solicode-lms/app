<?php
// Ce fichier est maintenu par ESSARRAJ Fouad



namespace Modules\PkgUtilisateurs\App\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ApprenantKonosyRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'MatriculeEtudiant' => 'required|max:255',
            'Nom' => 'required|max:255',
            'Prenom' => 'required|max:255',
            'Sexe' => 'required|max:255',
            'EtudiantActif' => 'required|max:255',
            'Diplome' => 'required|max:255',
            'Principale' => 'required|max:255',
            'LibelleLong' => 'required|max:255',
            'CodeDiplome' => 'required|max:255',
            'DateNaissance' => 'required|max:255',
            'DateInscription' => 'required|max:255',
            'LieuNaissance' => 'required|max:255',
            'CIN' => 'required|max:255',
            'NTelephone' => 'required|max:255',
            'Adresse' => 'required|max:255',
            'Nationalite' => 'required|max:255',
            'Nom_Arabe' => 'required|max:255',
            'Prenom_Arabe' => 'required|max:255',
            'NiveauScolaire' => 'required|max:255'
        ];
    }

    public function messages(): array
    {
        return [
            'MatriculeEtudiant.required' => __('validation.required', ['attribute' => __('PkgBlog::category.MatriculeEtudiant')]),
            'MatriculeEtudiant.max' => __('validation.MatriculeEtudiantMax'),
            'Nom.required' => __('validation.required', ['attribute' => __('PkgBlog::category.Nom')]),
            'Nom.max' => __('validation.NomMax'),
            'Prenom.required' => __('validation.required', ['attribute' => __('PkgBlog::category.Prenom')]),
            'Prenom.max' => __('validation.PrenomMax'),
            'Sexe.required' => __('validation.required', ['attribute' => __('PkgBlog::category.Sexe')]),
            'Sexe.max' => __('validation.SexeMax'),
            'EtudiantActif.required' => __('validation.required', ['attribute' => __('PkgBlog::category.EtudiantActif')]),
            'EtudiantActif.max' => __('validation.EtudiantActifMax'),
            'Diplome.required' => __('validation.required', ['attribute' => __('PkgBlog::category.Diplome')]),
            'Diplome.max' => __('validation.DiplomeMax'),
            'Principale.required' => __('validation.required', ['attribute' => __('PkgBlog::category.Principale')]),
            'Principale.max' => __('validation.PrincipaleMax'),
            'LibelleLong.required' => __('validation.required', ['attribute' => __('PkgBlog::category.LibelleLong')]),
            'LibelleLong.max' => __('validation.LibelleLongMax'),
            'CodeDiplome.required' => __('validation.required', ['attribute' => __('PkgBlog::category.CodeDiplome')]),
            'CodeDiplome.max' => __('validation.CodeDiplomeMax'),
            'DateNaissance.required' => __('validation.required', ['attribute' => __('PkgBlog::category.DateNaissance')]),
            'DateNaissance.max' => __('validation.DateNaissanceMax'),
            'DateInscription.required' => __('validation.required', ['attribute' => __('PkgBlog::category.DateInscription')]),
            'DateInscription.max' => __('validation.DateInscriptionMax'),
            'LieuNaissance.required' => __('validation.required', ['attribute' => __('PkgBlog::category.LieuNaissance')]),
            'LieuNaissance.max' => __('validation.LieuNaissanceMax'),
            'CIN.required' => __('validation.required', ['attribute' => __('PkgBlog::category.CIN')]),
            'CIN.max' => __('validation.CINMax'),
            'NTelephone.required' => __('validation.required', ['attribute' => __('PkgBlog::category.NTelephone')]),
            'NTelephone.max' => __('validation.NTelephoneMax'),
            'Adresse.required' => __('validation.required', ['attribute' => __('PkgBlog::category.Adresse')]),
            'Adresse.max' => __('validation.AdresseMax'),
            'Nationalite.required' => __('validation.required', ['attribute' => __('PkgBlog::category.Nationalite')]),
            'Nationalite.max' => __('validation.NationaliteMax'),
            'Nom_Arabe.required' => __('validation.required', ['attribute' => __('PkgBlog::category.Nom_Arabe')]),
            'Nom_Arabe.max' => __('validation.Nom_ArabeMax'),
            'Prenom_Arabe.required' => __('validation.required', ['attribute' => __('PkgBlog::category.Prenom_Arabe')]),
            'Prenom_Arabe.max' => __('validation.Prenom_ArabeMax'),
            'NiveauScolaire.required' => __('validation.required', ['attribute' => __('PkgBlog::category.NiveauScolaire')]),
            'NiveauScolaire.max' => __('validation.NiveauScolaireMax')
        ];
    }
}
