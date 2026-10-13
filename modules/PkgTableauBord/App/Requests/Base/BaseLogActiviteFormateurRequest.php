<?php
// Ce fichier est maintenu par ESSARRAJ Fouad



namespace Modules\PkgTableauBord\App\Requests\Base;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Modules\PkgTableauBord\Models\LogActiviteFormateur;

class BaseLogActiviteFormateurRequest extends FormRequest
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
            'formateur_id' => 'required',
            'type_activite' => 'required|string|max:255',
            'duree_secondes' => 'required|integer',
            'date_activite' => 'required',
            'semaine_annee' => 'required|string|max:255'
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
            'formateur_id.required' => __('validation.required', ['attribute' => __('PkgTableauBord::LogActiviteFormateur.formateur_id')]),
            'type_activite.required' => __('validation.required', ['attribute' => __('PkgTableauBord::LogActiviteFormateur.type_activite')]),
            'type_activite.max' => __('validation.type_activiteMax'),
            'duree_secondes.required' => __('validation.required', ['attribute' => __('PkgTableauBord::LogActiviteFormateur.duree_secondes')]),
            'date_activite.required' => __('validation.required', ['attribute' => __('PkgTableauBord::LogActiviteFormateur.date_activite')]),
            'semaine_annee.required' => __('validation.required', ['attribute' => __('PkgTableauBord::LogActiviteFormateur.semaine_annee')]),
            'semaine_annee.max' => __('validation.semaine_anneeMax')
        ];
    }

}
