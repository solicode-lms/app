<?php
// Ce fichier est maintenu par ESSARRAJ Fouad



namespace Modules\PkgApprentissage\App\Requests\Base;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Modules\PkgApprentissage\Models\RealisationChapitre;

class BaseRealisationChapitreRequest extends FormRequest
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
            'date_debut' => 'nullable',
            'date_fin' => 'nullable',
            'commentaire_formateur' => 'nullable|string',
            'realisation_ua_id' => 'required',
            'realisation_tache_id' => 'nullable',
            'chapitre_id' => 'required',
            'etat_realisation_chapitre_id' => 'nullable'
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
            'date_debut.required' => __('validation.required', ['attribute' => __('PkgApprentissage::RealisationChapitre.date_debut')]),
            'date_fin.required' => __('validation.required', ['attribute' => __('PkgApprentissage::RealisationChapitre.date_fin')]),
            'commentaire_formateur.required' => __('validation.required', ['attribute' => __('PkgApprentissage::RealisationChapitre.commentaire_formateur')]),
            'realisation_ua_id.required' => __('validation.required', ['attribute' => __('PkgApprentissage::RealisationChapitre.realisation_ua_id')]),
            'realisation_tache_id.required' => __('validation.required', ['attribute' => __('PkgApprentissage::RealisationChapitre.realisation_tache_id')]),
            'chapitre_id.required' => __('validation.required', ['attribute' => __('PkgApprentissage::RealisationChapitre.chapitre_id')]),
            'etat_realisation_chapitre_id.required' => __('validation.required', ['attribute' => __('PkgApprentissage::RealisationChapitre.etat_realisation_chapitre_id')])
        ];
    }

    
}
