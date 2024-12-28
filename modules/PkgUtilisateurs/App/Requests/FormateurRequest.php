<?php
// Ce fichier est maintenu par ESSARRAJ Fouad



namespace Modules\PkgUtilisateurs\App\Requests;

use Illuminate\Foundation\Http\FormRequest;

class FormateurRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'matricule' => 'required|max:255',
            'nom' => 'required|max:255',
            'prenom' => 'required|max:255',
            'prenom_arab' => 'required|max:255',
            'nom_arab' => 'required|max:255',
            'tele_num' => 'required|max:255',
            'adresse' => 'nullable|max:255',
            'diplome' => 'nullable|max:255',
            'echelle' => 'nullable',
            'echelon' => 'nullable',
            'profile_image' => 'required|max:255'
        ];
    }

    public function messages(): array
    {
        return [
            'matricule.required' => __('validation.required', ['attribute' => __('PkgUtilisateurs::Formateur.matricule')]),
            'matricule.max' => __('validation.matriculeMax'),
            'nom.required' => __('validation.required', ['attribute' => __('PkgUtilisateurs::Formateur.nom')]),
            'nom.max' => __('validation.nomMax'),
            'prenom.required' => __('validation.required', ['attribute' => __('PkgUtilisateurs::Formateur.prenom')]),
            'prenom.max' => __('validation.prenomMax'),
            'prenom_arab.required' => __('validation.required', ['attribute' => __('PkgUtilisateurs::Formateur.prenom_arab')]),
            'prenom_arab.max' => __('validation.prenom_arabMax'),
            'nom_arab.required' => __('validation.required', ['attribute' => __('PkgUtilisateurs::Formateur.nom_arab')]),
            'nom_arab.max' => __('validation.nom_arabMax'),
            'tele_num.required' => __('validation.required', ['attribute' => __('PkgUtilisateurs::Formateur.tele_num')]),
            'tele_num.max' => __('validation.tele_numMax'),
            'adresse.required' => __('validation.required', ['attribute' => __('PkgUtilisateurs::Formateur.adresse')]),
            'adresse.max' => __('validation.adresseMax'),
            'diplome.required' => __('validation.required', ['attribute' => __('PkgUtilisateurs::Formateur.diplome')]),
            'diplome.max' => __('validation.diplomeMax'),
            'echelle.required' => __('validation.required', ['attribute' => __('PkgUtilisateurs::Formateur.echelle')]),
            'echelle.max' => __('validation.echelleMax'),
            'echelon.required' => __('validation.required', ['attribute' => __('PkgUtilisateurs::Formateur.echelon')]),
            'echelon.max' => __('validation.echelonMax'),
            'profile_image.required' => __('validation.required', ['attribute' => __('PkgUtilisateurs::Formateur.profile_image')]),
            'profile_image.max' => __('validation.profile_imageMax')
        ];
    }
}
