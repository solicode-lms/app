<?php
// Ce fichier est maintenu par ESSARRAJ Fouad



namespace Modules\PkgGestionTaches\App\Requests\Base;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Modules\PkgGestionTaches\Models\RealisationTache;

class BaseRealisationTacheRequest extends FormRequest
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
            'dateDebut' => 'nullable',
            'dateFin' => 'nullable',
            'tache_id' => 'required',
            'realisation_projet_id' => 'required',
            'etat_realisation_tache_id' => 'nullable'
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
            'dateDebut.required' => __('validation.required', ['attribute' => __('PkgGestionTaches::RealisationTache.dateDebut')]),
            'dateFin.required' => __('validation.required', ['attribute' => __('PkgGestionTaches::RealisationTache.dateFin')]),
            'tache_id.required' => __('validation.required', ['attribute' => __('PkgGestionTaches::RealisationTache.tache_id')]),
            'realisation_projet_id.required' => __('validation.required', ['attribute' => __('PkgGestionTaches::RealisationTache.realisation_projet_id')]),
            'etat_realisation_tache_id.required' => __('validation.required', ['attribute' => __('PkgGestionTaches::RealisationTache.etat_realisation_tache_id')])
        ];
    }

    
}
