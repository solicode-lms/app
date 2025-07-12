<?php
// Ce fichier est maintenu par ESSARRAJ Fouad



namespace Modules\PkgCreationTache\App\Requests\Base;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Modules\PkgCreationTache\Models\PrioriteTache;

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
            'ordre' => 'required|integer',
            'nom' => 'required|string|max:255',
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
            'ordre.required' => __('validation.required', ['attribute' => __('PkgCreationTache::PrioriteTache.ordre')]),
            'nom.required' => __('validation.required', ['attribute' => __('PkgCreationTache::PrioriteTache.nom')]),
            'nom.max' => __('validation.nomMax'),
            'description.required' => __('validation.required', ['attribute' => __('PkgCreationTache::PrioriteTache.description')]),
            'formateur_id.required' => __('validation.required', ['attribute' => __('PkgCreationTache::PrioriteTache.formateur_id')])
        ];
    }

    
}
