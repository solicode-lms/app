<?php
// Ce fichier est maintenu par ESSARRAJ Fouad



namespace Modules\PkgApprenants\App\Requests\Base;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Modules\PkgApprenants\Models\Groupe;

class BaseGroupeRequest extends FormRequest
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
            'description' => 'nullable|string',
            'filiere_id' => 'nullable',
            'annee_formation_id' => 'nullable',
            'apprenants' => 'nullable|array',
            'formateurs' => 'nullable|array'
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
            'code.required' => __('validation.required', ['attribute' => __('PkgApprenants::Groupe.code')]),
            'code.max' => __('validation.codeMax'),
            'nom.required' => __('validation.required', ['attribute' => __('PkgApprenants::Groupe.nom')]),
            'nom.max' => __('validation.nomMax'),
            'description.required' => __('validation.required', ['attribute' => __('PkgApprenants::Groupe.description')]),
            'filiere_id.required' => __('validation.required', ['attribute' => __('PkgApprenants::Groupe.filiere_id')]),
            'annee_formation_id.required' => __('validation.required', ['attribute' => __('PkgApprenants::Groupe.annee_formation_id')]),
            'apprenants.required' => __('validation.required', ['attribute' => __('PkgApprenants::Groupe.apprenants')]),
            'apprenants.array' => __('validation.array', ['attribute' => __('PkgApprenants::Groupe.apprenants')]),
            'formateurs.required' => __('validation.required', ['attribute' => __('PkgApprenants::Groupe.formateurs')]),
            'formateurs.array' => __('validation.array', ['attribute' => __('PkgApprenants::Groupe.formateurs')])
        ];
    }

    protected function prepareForValidation()
    {
        $this->merge([
            'apprenants' => $this->has('apprenants') ? $this->apprenants : [],
            'formateurs' => $this->has('formateurs') ? $this->formateurs : []
        ]);

    }
}
