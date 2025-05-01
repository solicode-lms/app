<?php
// Ce fichier est maintenu par ESSARRAJ Fouad



namespace Modules\PkgGestionTaches\App\Requests\Base;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Modules\PkgGestionTaches\Models\HistoriqueRealisationTache;

class BaseHistoriqueRealisationTacheRequest extends FormRequest
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
            'dateModification' => 'required',
            'changement' => 'required|string',
            'realisation_tache_id' => 'required',
            'user_id' => 'nullable',
            'isFeedback' => 'required|boolean'
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
            'dateModification.required' => __('validation.required', ['attribute' => __('PkgGestionTaches::HistoriqueRealisationTache.dateModification')]),
            'changement.required' => __('validation.required', ['attribute' => __('PkgGestionTaches::HistoriqueRealisationTache.changement')]),
            'realisation_tache_id.required' => __('validation.required', ['attribute' => __('PkgGestionTaches::HistoriqueRealisationTache.realisation_tache_id')]),
            'user_id.required' => __('validation.required', ['attribute' => __('PkgGestionTaches::HistoriqueRealisationTache.user_id')]),
            'isFeedback.required' => __('validation.required', ['attribute' => __('PkgGestionTaches::HistoriqueRealisationTache.isFeedback')])
        ];
    }

    
}
