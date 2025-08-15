<?php
// Ce fichier est maintenu par ESSARRAJ Fouad



namespace Modules\PkgApprentissage\App\Requests\Base;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Modules\PkgApprentissage\Models\RealisationUaProjet;

class BaseRealisationUaProjetRequest extends FormRequest
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
            'realisation_tache_id' => 'required',
            'realisation_ua_id' => 'required',
            'note' => 'nullable',
            'bareme' => 'required',
            'remarque_formateur' => 'nullable|string',
            'date_debut' => 'nullable',
            'date_fin' => 'nullable'
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
            'realisation_tache_id.required' => __('validation.required', ['attribute' => __('PkgApprentissage::RealisationUaProjet.realisation_tache_id')]),
            'realisation_ua_id.required' => __('validation.required', ['attribute' => __('PkgApprentissage::RealisationUaProjet.realisation_ua_id')]),
            'note.required' => __('validation.required', ['attribute' => __('PkgApprentissage::RealisationUaProjet.note')]),
            'bareme.required' => __('validation.required', ['attribute' => __('PkgApprentissage::RealisationUaProjet.bareme')]),
            'remarque_formateur.required' => __('validation.required', ['attribute' => __('PkgApprentissage::RealisationUaProjet.remarque_formateur')]),
            'date_debut.required' => __('validation.required', ['attribute' => __('PkgApprentissage::RealisationUaProjet.date_debut')]),
            'date_fin.required' => __('validation.required', ['attribute' => __('PkgApprentissage::RealisationUaProjet.date_fin')])
        ];
    }

}
