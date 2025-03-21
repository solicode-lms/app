<?php
// Ce fichier est maintenu par ESSARRAJ Fouad



namespace Modules\PkgFormation\App\Requests\Base;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Modules\PkgFormation\Models\Filiere;

class BaseFiliereRequest extends FormRequest
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
            'code' => 'required|string|max:255',
            'nom' => 'nullable|string|max:255',
            'description' => 'nullable|string'
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
            'code.required' => __('validation.required', ['attribute' => __('PkgFormation::Filiere.code')]),
            'code.max' => __('validation.codeMax'),
            'nom.required' => __('validation.required', ['attribute' => __('PkgFormation::Filiere.nom')]),
            'nom.max' => __('validation.nomMax'),
            'description.required' => __('validation.required', ['attribute' => __('PkgFormation::Filiere.description')])
        ];
    }

    
}
