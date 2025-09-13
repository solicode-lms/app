<?php
// Ce fichier est maintenu par ESSARRAJ Fouad



namespace Modules\PkgFormation\App\Requests\Base;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Modules\PkgFormation\Models\Formateur;

class BaseFormateurRequest extends FormRequest
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
            'matricule' => 'required|string|max:255',
            'nom' => 'required|string|max:255',
            'prenom' => 'required|string|max:255',
            'prenom_arab' => 'nullable|string|max:255',
            'specialites' => 'nullable|array',
            'nom_arab' => 'nullable|string|max:255',
            'groupes' => 'nullable|array',
            'email' => 'nullable|string|max:255',
            'tele_num' => 'nullable|string|max:255',
            'adresse' => 'nullable|string|max:255',
            'diplome' => 'nullable|string|max:255',
            'echelle' => 'nullable|integer',
            'echelon' => 'nullable|integer',
            'profile_image' => 'nullable|string|max:255',
            'user_id' => 'nullable'
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
            'matricule.required' => __('validation.required', ['attribute' => __('PkgFormation::Formateur.matricule')]),
            'matricule.max' => __('validation.matriculeMax'),
            'nom.required' => __('validation.required', ['attribute' => __('PkgFormation::Formateur.nom')]),
            'nom.max' => __('validation.nomMax'),
            'prenom.required' => __('validation.required', ['attribute' => __('PkgFormation::Formateur.prenom')]),
            'prenom.max' => __('validation.prenomMax'),
            'prenom_arab.required' => __('validation.required', ['attribute' => __('PkgFormation::Formateur.prenom_arab')]),
            'prenom_arab.max' => __('validation.prenom_arabMax'),
            'specialites.required' => __('validation.required', ['attribute' => __('PkgFormation::Formateur.specialites')]),
            'specialites.array' => __('validation.array', ['attribute' => __('PkgFormation::Formateur.specialites')]),
            'nom_arab.required' => __('validation.required', ['attribute' => __('PkgFormation::Formateur.nom_arab')]),
            'nom_arab.max' => __('validation.nom_arabMax'),
            'groupes.required' => __('validation.required', ['attribute' => __('PkgFormation::Formateur.groupes')]),
            'groupes.array' => __('validation.array', ['attribute' => __('PkgFormation::Formateur.groupes')]),
            'email.required' => __('validation.required', ['attribute' => __('PkgFormation::Formateur.email')]),
            'email.max' => __('validation.emailMax'),
            'tele_num.required' => __('validation.required', ['attribute' => __('PkgFormation::Formateur.tele_num')]),
            'tele_num.max' => __('validation.tele_numMax'),
            'adresse.required' => __('validation.required', ['attribute' => __('PkgFormation::Formateur.adresse')]),
            'adresse.max' => __('validation.adresseMax'),
            'diplome.required' => __('validation.required', ['attribute' => __('PkgFormation::Formateur.diplome')]),
            'diplome.max' => __('validation.diplomeMax'),
            'echelle.required' => __('validation.required', ['attribute' => __('PkgFormation::Formateur.echelle')]),
            'echelon.required' => __('validation.required', ['attribute' => __('PkgFormation::Formateur.echelon')]),
            'profile_image.required' => __('validation.required', ['attribute' => __('PkgFormation::Formateur.profile_image')]),
            'profile_image.max' => __('validation.profile_imageMax'),
            'user_id.required' => __('validation.required', ['attribute' => __('PkgFormation::Formateur.user_id')])
        ];
    }

    /**
     * Prépare et sanitize les données avant la validation.
     *
     * - Pour les relations ManyToMany, on s'assure que le champ est toujours un tableau (vide si non fourni).
     * - Pour les champs éditables par rôles, on délègue au service la sanitation en fonction de l'utilisateur.
     *
     * @return void
     */
    protected function prepareForValidation()
    {
        $this->merge([
            'specialites' => $this->has('specialites') ? $this->specialites : [],
            'groupes' => $this->has('groupes') ? $this->groupes : []
        ]);
    }
}
