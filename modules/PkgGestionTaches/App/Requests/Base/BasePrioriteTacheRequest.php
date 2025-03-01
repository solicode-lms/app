<?php
// Ce fichier est maintenu par ESSARRAJ Fouad



namespace Modules\PkgGestionTaches\App\Requests\Base;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Modules\PkgGestionTaches\Models\PrioriteTache;

class BasePrioriteTacheRequest extends FormRequest
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
            'nom' => 'required|string|max:255',
            'ordre' => 'required|integer',
            'description' => 'nullable|string',
            'formateur_id' => 'required'
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
            'nom.required' => __('validation.required', ['attribute' => __('PkgGestionTaches::PrioriteTache.nom')]),
            'nom.max' => __('validation.nomMax'),
            'ordre.required' => __('validation.required', ['attribute' => __('PkgGestionTaches::PrioriteTache.ordre')]),
            'description.required' => __('validation.required', ['attribute' => __('PkgGestionTaches::PrioriteTache.description')]),
            'formateur_id.required' => __('validation.required', ['attribute' => __('PkgGestionTaches::PrioriteTache.formateur_id')])
        ];
    }

    
}
