<?php
// Ce fichier est maintenu par ESSARRAJ Fouad



namespace Modules\PkgFormation\App\Requests\Base;

use Illuminate\Foundation\Http\FormRequest;

class BaseModuleRequest extends FormRequest
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
            'masse_horaire' => 'required|string|max:255',
            'filiere_id' => 'required'
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
            'nom.required' => __('validation.required', ['attribute' => __('PkgFormation::Module.nom')]),
            'nom.max' => __('validation.nomMax'),
            'description.required' => __('validation.required', ['attribute' => __('PkgFormation::Module.description')]),
            'masse_horaire.required' => __('validation.required', ['attribute' => __('PkgFormation::Module.masse_horaire')]),
            'masse_horaire.max' => __('validation.masse_horaireMax'),
            'filiere_id.required' => __('validation.required', ['attribute' => __('PkgFormation::Module.filiere_id')])
        ];
    }
}
