<?php
// Ce fichier est maintenu par ESSARRAJ Fouad



namespace Modules\PkgCreationProjet\App\Requests\Base;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Modules\PkgCreationProjet\Models\LabelProjet;

class BaseLabelProjetRequest extends FormRequest
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
            'projet_id' => 'required',
            'sys_color_id' => 'nullable',
            'realisationTaches' => 'nullable|array',
            'taches' => 'nullable|array'
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
            'nom.required' => __('validation.required', ['attribute' => __('PkgCreationProjet::LabelProjet.nom')]),
            'nom.max' => __('validation.nomMax'),
            'description.required' => __('validation.required', ['attribute' => __('PkgCreationProjet::LabelProjet.description')]),
            'projet_id.required' => __('validation.required', ['attribute' => __('PkgCreationProjet::LabelProjet.projet_id')]),
            'sys_color_id.required' => __('validation.required', ['attribute' => __('PkgCreationProjet::LabelProjet.sys_color_id')]),
            'realisationTaches.required' => __('validation.required', ['attribute' => __('PkgCreationProjet::LabelProjet.realisationTaches')]),
            'realisationTaches.array' => __('validation.array', ['attribute' => __('PkgCreationProjet::LabelProjet.realisationTaches')]),
            'taches.required' => __('validation.required', ['attribute' => __('PkgCreationProjet::LabelProjet.taches')]),
            'taches.array' => __('validation.array', ['attribute' => __('PkgCreationProjet::LabelProjet.taches')])
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
            'realisationTaches' => $this->has('realisationTaches') ? $this->realisationTaches : [],
            'taches' => $this->has('taches') ? $this->taches : []
        ]);
    }
}
