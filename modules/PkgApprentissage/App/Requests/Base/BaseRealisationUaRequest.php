<?php
// Ce fichier est maintenu par ESSARRAJ Fouad



namespace Modules\PkgApprentissage\App\Requests\Base;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Modules\PkgApprentissage\Models\RealisationUa;

class BaseRealisationUaRequest extends FormRequest
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
            'unite_apprentissage_id' => 'required',
            'realisation_micro_competence_id' => 'required',
            'etat_realisation_ua_id' => 'nullable',
            'progression_cache' => 'required',
            'note_cache' => 'nullable',
            'bareme_cache' => 'nullable',
            'date_debut' => 'nullable',
            'date_fin' => 'nullable',
            'dernier_update' => 'nullable',
            'commentaire_formateur' => 'nullable|string',
            'progression_ideal_cache' => 'required',
            'taux_rythme_cache' => 'required'
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
            'unite_apprentissage_id.required' => __('validation.required', ['attribute' => __('PkgApprentissage::RealisationUa.unite_apprentissage_id')]),
            'realisation_micro_competence_id.required' => __('validation.required', ['attribute' => __('PkgApprentissage::RealisationUa.realisation_micro_competence_id')]),
            'etat_realisation_ua_id.required' => __('validation.required', ['attribute' => __('PkgApprentissage::RealisationUa.etat_realisation_ua_id')]),
            'progression_cache.required' => __('validation.required', ['attribute' => __('PkgApprentissage::RealisationUa.progression_cache')]),
            'note_cache.required' => __('validation.required', ['attribute' => __('PkgApprentissage::RealisationUa.note_cache')]),
            'bareme_cache.required' => __('validation.required', ['attribute' => __('PkgApprentissage::RealisationUa.bareme_cache')]),
            'date_debut.required' => __('validation.required', ['attribute' => __('PkgApprentissage::RealisationUa.date_debut')]),
            'date_fin.required' => __('validation.required', ['attribute' => __('PkgApprentissage::RealisationUa.date_fin')]),
            'dernier_update.required' => __('validation.required', ['attribute' => __('PkgApprentissage::RealisationUa.dernier_update')]),
            'commentaire_formateur.required' => __('validation.required', ['attribute' => __('PkgApprentissage::RealisationUa.commentaire_formateur')]),
            'progression_ideal_cache.required' => __('validation.required', ['attribute' => __('PkgApprentissage::RealisationUa.progression_ideal_cache')]),
            'taux_rythme_cache.required' => __('validation.required', ['attribute' => __('PkgApprentissage::RealisationUa.taux_rythme_cache')])
        ];
    }

}
