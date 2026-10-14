<?php
// Ce fichier est maintenu par ESSARRAJ Fouad



namespace Modules\PkgRealisationTache\App\Requests\Base;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Modules\PkgRealisationTache\Models\RevisionTache;

class BaseRevisionTacheRequest extends FormRequest
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
            'tentative' => 'required|integer',
            'date_demande' => 'required',
            'message_formateur' => 'nullable|string',
            'date_soumission' => 'nullable',
            'date_validation' => 'nullable',
            'est_valide' => 'required|boolean'
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
            'realisation_tache_id.required' => __('validation.required', ['attribute' => __('PkgRealisationTache::RevisionTache.realisation_tache_id')]),
            'tentative.required' => __('validation.required', ['attribute' => __('PkgRealisationTache::RevisionTache.tentative')]),
            'date_demande.required' => __('validation.required', ['attribute' => __('PkgRealisationTache::RevisionTache.date_demande')]),
            'message_formateur.required' => __('validation.required', ['attribute' => __('PkgRealisationTache::RevisionTache.message_formateur')]),
            'date_soumission.required' => __('validation.required', ['attribute' => __('PkgRealisationTache::RevisionTache.date_soumission')]),
            'date_validation.required' => __('validation.required', ['attribute' => __('PkgRealisationTache::RevisionTache.date_validation')]),
            'est_valide.required' => __('validation.required', ['attribute' => __('PkgRealisationTache::RevisionTache.est_valide')])
        ];
    }

}
