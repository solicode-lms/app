<?php
// Ce fichier est maintenu par ESSARRAJ Fouad



namespace Modules\PkgTableauBord\App\Requests\Base;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Modules\PkgTableauBord\Models\BordereauxOfficiel;

class BaseBordereauxOfficielRequest extends FormRequest
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
            'module_id' => 'required',
            'groupe_id' => 'required',
            'annee_formation_id' => 'nullable',
            'nb_apprenants' => 'required|integer',
            'taux_conformite_calcul' => 'required',
            'statut' => 'required|string|max:255',
            'date_scellement' => 'nullable',
            'hash_signature' => 'nullable|string|max:255',
            'genere_par_user_id' => 'nullable'
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
            'module_id.required' => __('validation.required', ['attribute' => __('PkgTableauBord::BordereauxOfficiel.module_id')]),
            'groupe_id.required' => __('validation.required', ['attribute' => __('PkgTableauBord::BordereauxOfficiel.groupe_id')]),
            'annee_formation_id.required' => __('validation.required', ['attribute' => __('PkgTableauBord::BordereauxOfficiel.annee_formation_id')]),
            'nb_apprenants.required' => __('validation.required', ['attribute' => __('PkgTableauBord::BordereauxOfficiel.nb_apprenants')]),
            'taux_conformite_calcul.required' => __('validation.required', ['attribute' => __('PkgTableauBord::BordereauxOfficiel.taux_conformite_calcul')]),
            'statut.required' => __('validation.required', ['attribute' => __('PkgTableauBord::BordereauxOfficiel.statut')]),
            'statut.max' => __('validation.statutMax'),
            'date_scellement.required' => __('validation.required', ['attribute' => __('PkgTableauBord::BordereauxOfficiel.date_scellement')]),
            'hash_signature.required' => __('validation.required', ['attribute' => __('PkgTableauBord::BordereauxOfficiel.hash_signature')]),
            'hash_signature.max' => __('validation.hash_signatureMax'),
            'genere_par_user_id.required' => __('validation.required', ['attribute' => __('PkgTableauBord::BordereauxOfficiel.genere_par_user_id')])
        ];
    }

}
