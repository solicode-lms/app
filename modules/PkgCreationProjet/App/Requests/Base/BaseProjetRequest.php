<?php
// Ce fichier est maintenu par ESSARRAJ Fouad



namespace Modules\PkgCreationProjet\App\Requests\Base;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Modules\PkgCreationProjet\Models\Projet;

class BaseProjetRequest extends FormRequest
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
            'session_formation_id' => 'nullable',
            'filiere_id' => 'required',
            'titre' => 'required|string|max:255',
            'travail_a_faire' => 'required|string',
            'critere_de_travail' => 'required|string',
            'formateur_id' => 'nullable',
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
            'session_formation_id.required' => __('validation.required', ['attribute' => __('PkgCreationProjet::Projet.session_formation_id')]),
            'filiere_id.required' => __('validation.required', ['attribute' => __('PkgCreationProjet::Projet.filiere_id')]),
            'titre.required' => __('validation.required', ['attribute' => __('PkgCreationProjet::Projet.titre')]),
            'titre.max' => __('validation.titreMax'),
            'travail_a_faire.required' => __('validation.required', ['attribute' => __('PkgCreationProjet::Projet.travail_a_faire')]),
            'critere_de_travail.required' => __('validation.required', ['attribute' => __('PkgCreationProjet::Projet.critere_de_travail')]),
            'formateur_id.required' => __('validation.required', ['attribute' => __('PkgCreationProjet::Projet.formateur_id')]),
            'description.required' => __('validation.required', ['attribute' => __('PkgCreationProjet::Projet.description')])
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
        // En création, on ne touche pas au payload (même traitement existant)
        $id = $this->route('projet')
        ?? $this->route('projet')
        ?? null;

        if (!$id) {
            return;
        }

        $model = \Modules\PkgCreationProjet\Models\Projet::find($id);
        if (!$model) {
            return;
        }

        /** @var \Modules\PkgCreationProjet\Services\ProjetService $service */
        $service = app(\Modules\PkgCreationProjet\Services\ProjetService::class);
        $user    = $this->user() ?: \Illuminate\Support\Facades\Auth::user();

        // Déléguer au service la sanitation par rôles
        [$sanitized] = $service->sanitizePayloadByRoles(
            $this->all(),
            $model,
            $user
        );

        // Remplacer la requête par la version nettoyée/merge
        $this->replace($sanitized);
    }
}
