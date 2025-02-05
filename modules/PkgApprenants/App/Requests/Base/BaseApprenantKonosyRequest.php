<?php
// Ce fichier est maintenu par ESSARRAJ Fouad



namespace Modules\PkgApprenants\App\Requests\Base;

use Illuminate\Foundation\Http\FormRequest;

class BaseApprenantKonosyRequest extends FormRequest
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
            'MatriculeEtudiant' => 'required|string|max:255',
            'Nom' => 'required|string|max:255',
            'Prenom' => 'required|string|max:255',
            'Sexe' => 'required|string|max:255',
            'EtudiantActif' => 'required|string|max:255',
            'Diplome' => 'nullable|string|max:255',
            'Principale' => 'nullable|string|max:255',
            'LibelleLong' => 'nullable|string|max:255',
            'CodeDiplome' => 'nullable|string|max:255',
            'DateNaissance' => 'nullable|string|max:255',
            'DateInscription' => 'nullable|string|max:255',
            'LieuNaissance' => 'nullable|string|max:255',
            'CIN' => 'nullable|string|max:255',
            'NTelephone' => 'nullable|string|max:255',
            'Adresse' => 'nullable|string',
            'Nationalite' => 'nullable|string|max:255',
            'Nom_Arabe' => 'nullable|string|max:255',
            'Prenom_Arabe' => 'nullable|string|max:255',
            'NiveauScolaire' => 'nullable|string|max:255'
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
            'MatriculeEtudiant.required' => __('validation.required', ['attribute' => __('PkgApprenants::ApprenantKonosy.MatriculeEtudiant')]),
            'MatriculeEtudiant.max' => __('validation.MatriculeEtudiantMax'),
            'Nom.required' => __('validation.required', ['attribute' => __('PkgApprenants::ApprenantKonosy.Nom')]),
            'Nom.max' => __('validation.NomMax'),
            'Prenom.required' => __('validation.required', ['attribute' => __('PkgApprenants::ApprenantKonosy.Prenom')]),
            'Prenom.max' => __('validation.PrenomMax'),
            'Sexe.required' => __('validation.required', ['attribute' => __('PkgApprenants::ApprenantKonosy.Sexe')]),
            'Sexe.max' => __('validation.SexeMax'),
            'EtudiantActif.required' => __('validation.required', ['attribute' => __('PkgApprenants::ApprenantKonosy.EtudiantActif')]),
            'EtudiantActif.max' => __('validation.EtudiantActifMax'),
            'Diplome.required' => __('validation.required', ['attribute' => __('PkgApprenants::ApprenantKonosy.Diplome')]),
            'Diplome.max' => __('validation.DiplomeMax'),
            'Principale.required' => __('validation.required', ['attribute' => __('PkgApprenants::ApprenantKonosy.Principale')]),
            'Principale.max' => __('validation.PrincipaleMax'),
            'LibelleLong.required' => __('validation.required', ['attribute' => __('PkgApprenants::ApprenantKonosy.LibelleLong')]),
            'LibelleLong.max' => __('validation.LibelleLongMax'),
            'CodeDiplome.required' => __('validation.required', ['attribute' => __('PkgApprenants::ApprenantKonosy.CodeDiplome')]),
            'CodeDiplome.max' => __('validation.CodeDiplomeMax'),
            'DateNaissance.required' => __('validation.required', ['attribute' => __('PkgApprenants::ApprenantKonosy.DateNaissance')]),
            'DateNaissance.max' => __('validation.DateNaissanceMax'),
            'DateInscription.required' => __('validation.required', ['attribute' => __('PkgApprenants::ApprenantKonosy.DateInscription')]),
            'DateInscription.max' => __('validation.DateInscriptionMax'),
            'LieuNaissance.required' => __('validation.required', ['attribute' => __('PkgApprenants::ApprenantKonosy.LieuNaissance')]),
            'LieuNaissance.max' => __('validation.LieuNaissanceMax'),
            'CIN.required' => __('validation.required', ['attribute' => __('PkgApprenants::ApprenantKonosy.CIN')]),
            'CIN.max' => __('validation.CINMax'),
            'NTelephone.required' => __('validation.required', ['attribute' => __('PkgApprenants::ApprenantKonosy.NTelephone')]),
            'NTelephone.max' => __('validation.NTelephoneMax'),
            'Adresse.required' => __('validation.required', ['attribute' => __('PkgApprenants::ApprenantKonosy.Adresse')]),
            'Nationalite.required' => __('validation.required', ['attribute' => __('PkgApprenants::ApprenantKonosy.Nationalite')]),
            'Nationalite.max' => __('validation.NationaliteMax'),
            'Nom_Arabe.required' => __('validation.required', ['attribute' => __('PkgApprenants::ApprenantKonosy.Nom_Arabe')]),
            'Nom_Arabe.max' => __('validation.Nom_ArabeMax'),
            'Prenom_Arabe.required' => __('validation.required', ['attribute' => __('PkgApprenants::ApprenantKonosy.Prenom_Arabe')]),
            'Prenom_Arabe.max' => __('validation.Prenom_ArabeMax'),
            'NiveauScolaire.required' => __('validation.required', ['attribute' => __('PkgApprenants::ApprenantKonosy.NiveauScolaire')]),
            'NiveauScolaire.max' => __('validation.NiveauScolaireMax')
        ];
    }
}
