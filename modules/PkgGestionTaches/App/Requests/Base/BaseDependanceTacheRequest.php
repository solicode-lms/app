<?php
// Ce fichier est maintenu par ESSARRAJ Fouad



namespace Modules\PkgGestionTaches\App\Requests\Base;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Modules\PkgGestionTaches\Models\DependanceTache;

class BaseDependanceTacheRequest extends FormRequest
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
            'tache_source_id' => 'required',
            'tache_cible_id' => 'required',
            'type_dependance_tache_id' => 'nullable'
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
            'tache_source_id.required' => __('validation.required', ['attribute' => __('PkgGestionTaches::DependanceTache.tache_source_id')]),
            'tache_cible_id.required' => __('validation.required', ['attribute' => __('PkgGestionTaches::DependanceTache.tache_cible_id')]),
            'type_dependance_tache_id.required' => __('validation.required', ['attribute' => __('PkgGestionTaches::DependanceTache.type_dependance_tache_id')])
        ];
    }

    
}
