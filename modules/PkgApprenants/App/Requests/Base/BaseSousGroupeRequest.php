<?php
// Ce fichier est maintenu par ESSARRAJ Fouad



namespace Modules\PkgApprenants\App\Requests\Base;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Modules\PkgApprenants\Models\SousGroupe;

class BaseSousGroupeRequest extends FormRequest
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
            'description' => 'nullable|string',
            'groupe_id' => 'required',
            'apprenants' => 'nullable|array'
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
            'nom.required' => __('validation.required', ['attribute' => __('PkgApprenants::SousGroupe.nom')]),
            'nom.max' => __('validation.nomMax'),
            'description.required' => __('validation.required', ['attribute' => __('PkgApprenants::SousGroupe.description')]),
            'groupe_id.required' => __('validation.required', ['attribute' => __('PkgApprenants::SousGroupe.groupe_id')]),
            'apprenants.required' => __('validation.required', ['attribute' => __('PkgApprenants::SousGroupe.apprenants')]),
            'apprenants.array' => __('validation.array', ['attribute' => __('PkgApprenants::SousGroupe.apprenants')])
        ];
    }

    
}
