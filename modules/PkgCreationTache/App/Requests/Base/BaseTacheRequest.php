<?php
// Ce fichier est maintenu par ESSARRAJ Fouad



namespace Modules\PkgCreationTache\App\Requests\Base;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Modules\PkgCreationTache\Models\Tache;

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
            'priorite' => 'nullable|integer',
            'titre' => 'required|string|max:255',
            'projet_id' => 'required',
            'description' => 'nullable|string',
            'dateDebut' => 'nullable',
            'dateFin' => 'nullable',
            'note' => 'nullable',
            'phase_evaluation_id' => 'nullable',
            'chapitre_id' => 'nullable',
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
            'ordre.required' => __('validation.required', ['attribute' => __('PkgCreationTache::Tache.ordre')]),
            'priorite.required' => __('validation.required', ['attribute' => __('PkgCreationTache::Tache.priorite')]),
            'titre.required' => __('validation.required', ['attribute' => __('PkgCreationTache::Tache.titre')]),
            'titre.max' => __('validation.titreMax'),
            'projet_id.required' => __('validation.required', ['attribute' => __('PkgCreationTache::Tache.projet_id')]),
            'description.required' => __('validation.required', ['attribute' => __('PkgCreationTache::Tache.description')]),
            'dateDebut.required' => __('validation.required', ['attribute' => __('PkgCreationTache::Tache.dateDebut')]),
            'dateFin.required' => __('validation.required', ['attribute' => __('PkgCreationTache::Tache.dateFin')]),
            'note.required' => __('validation.required', ['attribute' => __('PkgCreationTache::Tache.note')]),
            'phase_evaluation_id.required' => __('validation.required', ['attribute' => __('PkgCreationTache::Tache.phase_evaluation_id')]),
            'chapitre_id.required' => __('validation.required', ['attribute' => __('PkgCreationTache::Tache.chapitre_id')]),
            'livrables.required' => __('validation.required', ['attribute' => __('PkgCreationTache::Tache.livrables')]),
            'livrables.array' => __('validation.array', ['attribute' => __('PkgCreationTache::Tache.livrables')])
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
            'livrables' => $this->has('livrables') ? $this->livrables : []
        ]);
    }
}
