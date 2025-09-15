<?php
// Ce fichier est maintenu par ESSARRAJ Fouad



namespace Modules\PkgRealisationProjets\App\Requests\Base;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Modules\PkgRealisationProjets\Models\RealisationProjet;

class BaseRealisationProjetRequest extends FormRequest
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
            'affectation_projet_id' => 'required',
            'apprenant_id' => 'required',
            'etats_realisation_projet_id' => 'required',
            'progression_validation_cache' => 'nullable',
            'note_cache' => 'nullable',
            'date_debut' => 'required',
            'date_fin' => 'nullable',
            'bareme_cache' => 'nullable',
            'progression_execution_cache' => 'nullable',
            'rapport' => 'nullable|string'
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
            'affectation_projet_id.required' => __('validation.required', ['attribute' => __('PkgRealisationProjets::RealisationProjet.affectation_projet_id')]),
            'apprenant_id.required' => __('validation.required', ['attribute' => __('PkgRealisationProjets::RealisationProjet.apprenant_id')]),
            'etats_realisation_projet_id.required' => __('validation.required', ['attribute' => __('PkgRealisationProjets::RealisationProjet.etats_realisation_projet_id')]),
            'progression_validation_cache.required' => __('validation.required', ['attribute' => __('PkgRealisationProjets::RealisationProjet.progression_validation_cache')]),
            'note_cache.required' => __('validation.required', ['attribute' => __('PkgRealisationProjets::RealisationProjet.note_cache')]),
            'date_debut.required' => __('validation.required', ['attribute' => __('PkgRealisationProjets::RealisationProjet.date_debut')]),
            'date_fin.required' => __('validation.required', ['attribute' => __('PkgRealisationProjets::RealisationProjet.date_fin')]),
            'bareme_cache.required' => __('validation.required', ['attribute' => __('PkgRealisationProjets::RealisationProjet.bareme_cache')]),
            'progression_execution_cache.required' => __('validation.required', ['attribute' => __('PkgRealisationProjets::RealisationProjet.progression_execution_cache')]),
            'rapport.required' => __('validation.required', ['attribute' => __('PkgRealisationProjets::RealisationProjet.rapport')])
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
        $id = $this->route('realisationProjet')
        ?? $this->route('realisation_projet')
        ?? null;

        if (!$id) {
            return;
        }

        $model = \Modules\PkgRealisationProjets\Models\RealisationProjet::find($id);
        if (!$model) {
            return;
        }

        /** @var \Modules\PkgRealisationProjets\Services\RealisationProjetService $service */
        $service = app(\Modules\PkgRealisationProjets\Services\RealisationProjetService::class);
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
