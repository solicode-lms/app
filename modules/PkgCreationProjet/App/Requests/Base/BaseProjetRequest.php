<?php
// Ce fichier est maintenu par ESSARRAJ Fouad



namespace Modules\PkgCreationProjet\App\Requests\Base;

use Illuminate\Foundation\Http\FormRequest;

class BaseProjetRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'titre' => 'required|max:255',
            'travail_a_faire' => 'required',
            'critere_de_travail' => 'required',
            'nombre_jour' => 'required',
            'description' => 'nullable',
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
            'nombre_jour.required' => __('validation.required', ['attribute' => __('PkgCreationProjet::Projet.nombre_jour')]),
            'nombre_jour.max' => __('validation.nombre_jourMax'),
            'description.required' => __('validation.required', ['attribute' => __('PkgCreationProjet::Projet.description')]),
            'description.max' => __('validation.descriptionMax'),
            'formateur_id.required' => __('validation.required', ['attribute' => __('PkgCreationProjet::Projet.formateur_id')]),
            'formateur_id.max' => __('validation.formateur_idMax')
        ];
    }
}
