<?php
// Ce fichier est maintenu par ESSARRAJ Fouad



namespace Modules\PkgUtilisateurs\App\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ApprenantRequest extends FormRequest
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
            'profile_image' => 'required|max:255',
            'date_inscription' => 'required',
            'ville_id' => 'required',
            'groupe_id' => 'required',
            'niveaux_scolaires_id' => 'required'
        ];
    }

    public function messages(): array
    {
        return [
            'nom.required' => __('validation.required', ['attribute' => __('PkgBlog::category.nom')]),
            'nom.max' => __('validation.nomMax'),
            'prenom.required' => __('validation.required', ['attribute' => __('PkgBlog::category.prenom')]),
            'prenom.max' => __('validation.prenomMax'),
            'prenom_arab.required' => __('validation.required', ['attribute' => __('PkgBlog::category.prenom_arab')]),
            'prenom_arab.max' => __('validation.prenom_arabMax'),
            'nom_arab.required' => __('validation.required', ['attribute' => __('PkgBlog::category.nom_arab')]),
            'nom_arab.max' => __('validation.nom_arabMax'),
            'tele_num.required' => __('validation.required', ['attribute' => __('PkgBlog::category.tele_num')]),
            'tele_num.max' => __('validation.tele_numMax'),
            'profile_image.required' => __('validation.required', ['attribute' => __('PkgBlog::category.profile_image')]),
            'profile_image.max' => __('validation.profile_imageMax'),
            'date_inscription.required' => __('validation.required', ['attribute' => __('PkgBlog::category.date_inscription')]),
            'date_inscription.max' => __('validation.date_inscriptionMax'),
            'ville_id.required' => __('validation.required', ['attribute' => __('PkgBlog::category.ville_id')]),
            'ville_id.max' => __('validation.ville_idMax'),
            'groupe_id.required' => __('validation.required', ['attribute' => __('PkgBlog::category.groupe_id')]),
            'groupe_id.max' => __('validation.groupe_idMax'),
            'niveaux_scolaires_id.required' => __('validation.required', ['attribute' => __('PkgBlog::category.niveaux_scolaires_id')]),
            'niveaux_scolaires_id.max' => __('validation.niveaux_scolaires_idMax')
        ];
    }
}
