<?php
// Ce fichier est maintenu par ESSARRAJ Fouad



namespace Modules\PkgRealisationTache\App\Requests\Base;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Modules\PkgRealisationTache\Models\RealisationTache;

class BaseRealisationTacheRequest extends FormRequest
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
            'tache_id' => 'required',
            'etat_realisation_tache_id' => 'required',
            'realisation_projet_id' => 'required',
            'dateDebut' => 'required',
            'dateFin' => 'nullable',
            'remarque_evaluateur' => 'nullable|string',
            'note' => 'nullable',
            'is_live_coding' => 'nullable|boolean',
            'remarques_formateur' => 'nullable|string',
            'remarques_apprenant' => 'nullable|string',
            'tache_affectation_id' => 'required'
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
            'tache_id.required' => __('validation.required', ['attribute' => __('PkgRealisationTache::RealisationTache.tache_id')]),
            'etat_realisation_tache_id.required' => __('validation.required', ['attribute' => __('PkgRealisationTache::RealisationTache.etat_realisation_tache_id')]),
            'realisation_projet_id.required' => __('validation.required', ['attribute' => __('PkgRealisationTache::RealisationTache.realisation_projet_id')]),
            'dateDebut.required' => __('validation.required', ['attribute' => __('PkgRealisationTache::RealisationTache.dateDebut')]),
            'dateFin.required' => __('validation.required', ['attribute' => __('PkgRealisationTache::RealisationTache.dateFin')]),
            'remarque_evaluateur.required' => __('validation.required', ['attribute' => __('PkgRealisationTache::RealisationTache.remarque_evaluateur')]),
            'note.required' => __('validation.required', ['attribute' => __('PkgRealisationTache::RealisationTache.note')]),
            'is_live_coding.required' => __('validation.required', ['attribute' => __('PkgRealisationTache::RealisationTache.is_live_coding')]),
            'remarques_formateur.required' => __('validation.required', ['attribute' => __('PkgRealisationTache::RealisationTache.remarques_formateur')]),
            'remarques_apprenant.required' => __('validation.required', ['attribute' => __('PkgRealisationTache::RealisationTache.remarques_apprenant')]),
            'tache_affectation_id.required' => __('validation.required', ['attribute' => __('PkgRealisationTache::RealisationTache.tache_affectation_id')])
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
        $id = $this->route('realisationTache')
        ?? $this->route('realisation_tache')
        ?? null;

        if (!$id) {
            return;
        }

        $model = \Modules\PkgRealisationTache\Models\RealisationTache::find($id);
        if (!$model) {
            return;
        }

        /** @var \Modules\PkgRealisationTache\Services\RealisationTacheService $service */
        $service = app(\Modules\PkgRealisationTache\Services\RealisationTacheService::class);
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
