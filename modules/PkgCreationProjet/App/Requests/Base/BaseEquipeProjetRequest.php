<?php
// Ce fichier est maintenu par ESSARRAJ Fouad



namespace Modules\PkgCreationProjet\App\Requests\Base;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Modules\PkgCreationProjet\Models\EquipeProjet;

class BaseEquipeProjetRequest extends FormRequest
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
            'apprenants' => 'nullable|array',
            'description' => 'nullable|string',
            'sys_color_id' => 'nullable',
            'projet_id' => 'required'
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
            'nom.required' => __('validation.required', ['attribute' => __('PkgCreationProjet::EquipeProjet.nom')]),
            'nom.max' => __('validation.nomMax'),
            'apprenants.required' => __('validation.required', ['attribute' => __('PkgCreationProjet::EquipeProjet.apprenants')]),
            'apprenants.array' => __('validation.array', ['attribute' => __('PkgCreationProjet::EquipeProjet.apprenants')]),
            'description.required' => __('validation.required', ['attribute' => __('PkgCreationProjet::EquipeProjet.description')]),
            'sys_color_id.required' => __('validation.required', ['attribute' => __('PkgCreationProjet::EquipeProjet.sys_color_id')]),
            'projet_id.required' => __('validation.required', ['attribute' => __('PkgCreationProjet::EquipeProjet.projet_id')])
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
            'apprenants' => $this->has('apprenants') ? $this->apprenants : []
        ]);
    }
}
