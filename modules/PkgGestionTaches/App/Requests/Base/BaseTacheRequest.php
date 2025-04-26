<?php
// Ce fichier est maintenu par ESSARRAJ Fouad



namespace Modules\PkgGestionTaches\App\Requests\Base;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Modules\PkgGestionTaches\Models\Tache;

class BaseTacheRequest extends FormRequest
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
            'ordre' => 'nullable|integer',
            'titre' => 'required|string|max:255',
            'priorite_tache_id' => 'nullable',
            'description' => 'nullable|string',
            'dateDebut' => 'nullable',
            'dateFin' => 'nullable',
            'projet_id' => 'required',
            'livrables' => 'nullable|array'
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
            'ordre.required' => __('validation.required', ['attribute' => __('PkgGestionTaches::Tache.ordre')]),
            'titre.required' => __('validation.required', ['attribute' => __('PkgGestionTaches::Tache.titre')]),
            'titre.max' => __('validation.titreMax'),
            'priorite_tache_id.required' => __('validation.required', ['attribute' => __('PkgGestionTaches::Tache.priorite_tache_id')]),
            'description.required' => __('validation.required', ['attribute' => __('PkgGestionTaches::Tache.description')]),
            'dateDebut.required' => __('validation.required', ['attribute' => __('PkgGestionTaches::Tache.dateDebut')]),
            'dateFin.required' => __('validation.required', ['attribute' => __('PkgGestionTaches::Tache.dateFin')]),
            'projet_id.required' => __('validation.required', ['attribute' => __('PkgGestionTaches::Tache.projet_id')]),
            'livrables.required' => __('validation.required', ['attribute' => __('PkgGestionTaches::Tache.livrables')]),
            'livrables.array' => __('validation.array', ['attribute' => __('PkgGestionTaches::Tache.livrables')])
        ];
    }

    
}
