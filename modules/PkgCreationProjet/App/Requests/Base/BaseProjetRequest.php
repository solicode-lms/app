<?php
// Ce fichier est maintenu par ESSARRAJ Fouad



namespace Modules\PkgCreationProjet\App\Requests\Base;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Modules\PkgCreationProjet\Models\Projet;

class BaseProjetRequest extends FormRequest
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
            'titre' => 'required|string|max:255',
            'travail_a_faire' => 'required|string',
            'critere_de_travail' => 'required|string',
            'nombre_jour' => 'required|integer',
            'description' => 'nullable|string',
            'filiere_id' => 'nullable',
            'formateur_id' => 'required'
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
            'titre.required' => __('validation.required', ['attribute' => __('PkgCreationProjet::Projet.titre')]),
            'titre.max' => __('validation.titreMax'),
            'travail_a_faire.required' => __('validation.required', ['attribute' => __('PkgCreationProjet::Projet.travail_a_faire')]),
            'critere_de_travail.required' => __('validation.required', ['attribute' => __('PkgCreationProjet::Projet.critere_de_travail')]),
            'nombre_jour.required' => __('validation.required', ['attribute' => __('PkgCreationProjet::Projet.nombre_jour')]),
            'description.required' => __('validation.required', ['attribute' => __('PkgCreationProjet::Projet.description')]),
            'filiere_id.required' => __('validation.required', ['attribute' => __('PkgCreationProjet::Projet.filiere_id')]),
            'formateur_id.required' => __('validation.required', ['attribute' => __('PkgCreationProjet::Projet.formateur_id')])
        ];
    }

    
}
