<?php
// Ce fichier est maintenu par ESSARRAJ Fouad



namespace Modules\PkgApprentissage\App\Requests\Base;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Modules\PkgApprentissage\Models\RealisationMicroCompetence;

class BaseRealisationMicroCompetenceRequest extends FormRequest
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
            'micro_competence_id' => 'required',
            'apprenant_id' => 'required',
            'progression_cache' => 'required',
            'note_cache' => 'nullable',
            'etat_realisation_micro_competence_id' => 'nullable',
            'bareme_cache' => 'nullable',
            'commentaire_formateur' => 'nullable|string',
            'date_debut' => 'nullable',
            'date_fin' => 'nullable',
            'dernier_update' => 'nullable',
            'realisation_competence_id' => 'required',
            'lien_livrable' => 'nullable|string|max:255|url',
            'progression_ideal_cache' => 'nullable',
            'taux_rythme_cache' => 'nullable'
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
            'micro_competence_id.required' => __('validation.required', ['attribute' => __('PkgApprentissage::RealisationMicroCompetence.micro_competence_id')]),
            'apprenant_id.required' => __('validation.required', ['attribute' => __('PkgApprentissage::RealisationMicroCompetence.apprenant_id')]),
            'progression_cache.required' => __('validation.required', ['attribute' => __('PkgApprentissage::RealisationMicroCompetence.progression_cache')]),
            'note_cache.required' => __('validation.required', ['attribute' => __('PkgApprentissage::RealisationMicroCompetence.note_cache')]),
            'etat_realisation_micro_competence_id.required' => __('validation.required', ['attribute' => __('PkgApprentissage::RealisationMicroCompetence.etat_realisation_micro_competence_id')]),
            'bareme_cache.required' => __('validation.required', ['attribute' => __('PkgApprentissage::RealisationMicroCompetence.bareme_cache')]),
            'commentaire_formateur.required' => __('validation.required', ['attribute' => __('PkgApprentissage::RealisationMicroCompetence.commentaire_formateur')]),
            'date_debut.required' => __('validation.required', ['attribute' => __('PkgApprentissage::RealisationMicroCompetence.date_debut')]),
            'date_fin.required' => __('validation.required', ['attribute' => __('PkgApprentissage::RealisationMicroCompetence.date_fin')]),
            'dernier_update.required' => __('validation.required', ['attribute' => __('PkgApprentissage::RealisationMicroCompetence.dernier_update')]),
            'realisation_competence_id.required' => __('validation.required', ['attribute' => __('PkgApprentissage::RealisationMicroCompetence.realisation_competence_id')]),
            'lien_livrable.required' => __('validation.required', ['attribute' => __('PkgApprentissage::RealisationMicroCompetence.lien_livrable')]),
            'lien_livrable.max' => __('validation.lien_livrableMax'),
            'progression_ideal_cache.required' => __('validation.required', ['attribute' => __('PkgApprentissage::RealisationMicroCompetence.progression_ideal_cache')]),
            'taux_rythme_cache.required' => __('validation.required', ['attribute' => __('PkgApprentissage::RealisationMicroCompetence.taux_rythme_cache')])
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
        $id = $this->route('realisationMicroCompetence')
        ?? $this->route('realisation_micro_competence')
        ?? null;

        if (!$id) {
            return;
        }

        $model = \Modules\PkgApprentissage\Models\RealisationMicroCompetence::find($id);
        if (!$model) {
            return;
        }

        /** @var \Modules\PkgApprentissage\Services\RealisationMicroCompetenceService $service */
        $service = app(\Modules\PkgApprentissage\Services\RealisationMicroCompetenceService::class);
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
