<?php
// Ce fichier est maintenu par ESSARRAJ Fouad



namespace Modules\PkgCreationProjet\App\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ProjetRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'titre' => 'required|max:255',
            'travail_a_faire' => 'required|max:255',
            'critere_de_travail' => 'required|max:255',
            'description' => 'required|max:255',
            'date_debut' => 'required',
            'date_fin' => 'required',
            'formateur_id' => 'required'
        ];
    }

    public function messages(): array
    {
        return [
            'titre.required' => __('validation.required', ['attribute' => __('PkgCreationProjet::Projet.titre')]),
            'titre.max' => __('validation.titreMax'),
            'travail_a_faire.required' => __('validation.required', ['attribute' => __('PkgCreationProjet::Projet.travail_a_faire')]),
            'travail_a_faire.max' => __('validation.travail_a_faireMax'),
            'critere_de_travail.required' => __('validation.required', ['attribute' => __('PkgCreationProjet::Projet.critere_de_travail')]),
            'critere_de_travail.max' => __('validation.critere_de_travailMax'),
            'description.required' => __('validation.required', ['attribute' => __('PkgCreationProjet::Projet.description')]),
            'description.max' => __('validation.descriptionMax'),
            'date_debut.required' => __('validation.required', ['attribute' => __('PkgCreationProjet::Projet.date_debut')]),
            'date_debut.max' => __('validation.date_debutMax'),
            'date_fin.required' => __('validation.required', ['attribute' => __('PkgCreationProjet::Projet.date_fin')]),
            'date_fin.max' => __('validation.date_finMax'),
            'formateur_id.required' => __('validation.required', ['attribute' => __('PkgCreationProjet::Projet.formateur_id')]),
            'formateur_id.max' => __('validation.formateur_idMax')
        ];
    }
}
