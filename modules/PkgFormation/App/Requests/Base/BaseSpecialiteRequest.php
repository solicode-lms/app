<?php
// Ce fichier est maintenu par ESSARRAJ Fouad



namespace Modules\PkgFormation\App\Requests\Base;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Modules\PkgFormation\Models\Specialite;

class BaseSpecialiteRequest extends FormRequest
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
            'nom.required' => __('validation.required', ['attribute' => __('PkgFormation::Specialite.nom')]),
            'nom.max' => __('validation.nomMax'),
            'description.required' => __('validation.required', ['attribute' => __('PkgFormation::Specialite.description')]),
            'formateurs.required' => __('validation.required', ['attribute' => __('PkgFormation::Specialite.formateurs')]),
            'formateurs.array' => __('validation.array', ['attribute' => __('PkgFormation::Specialite.formateurs')])
        ];
    }

    /**
     * Prépare et sanitize les données avant la validation.
     *
     * - Pour les relations ManyToMany, on s'assure que le champ est toujours un tableau (vide si non fourni).
     * - Pour les champs éditables par rôles, on délègue au service la sanitation en fonction de l'utilisateur.
     *
     * @return void
     */
    protected function prepareForValidation()
    {
        $this->merge([
            'formateurs' => $this->has('formateurs') ? $this->formateurs : []
        ]);
    }
}
