<?php
// Ce fichier est maintenu par ESSARRAJ Fouad



namespace Modules\PkgEvaluateurs\App\Requests\Base;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Modules\PkgEvaluateurs\Models\EvaluationRealisationProjet;

class BaseEvaluationRealisationProjetRequest extends FormRequest
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
            'realisation_projet_id' => 'required',
            'evaluateur_id' => 'required',
            'date_evaluation' => 'required',
            'etat_evaluation_projet_id' => 'nullable',
            'remarques' => 'nullable|string'
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
            'realisation_projet_id.required' => __('validation.required', ['attribute' => __('PkgEvaluateurs::EvaluationRealisationProjet.realisation_projet_id')]),
            'evaluateur_id.required' => __('validation.required', ['attribute' => __('PkgEvaluateurs::EvaluationRealisationProjet.evaluateur_id')]),
            'date_evaluation.required' => __('validation.required', ['attribute' => __('PkgEvaluateurs::EvaluationRealisationProjet.date_evaluation')]),
            'etat_evaluation_projet_id.required' => __('validation.required', ['attribute' => __('PkgEvaluateurs::EvaluationRealisationProjet.etat_evaluation_projet_id')]),
            'remarques.required' => __('validation.required', ['attribute' => __('PkgEvaluateurs::EvaluationRealisationProjet.remarques')])
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
        $id = $this->route('evaluationRealisationProjet')
        ?? $this->route('evaluation_realisation_projet')
        ?? null;

        if (!$id) {
            return;
        }

        $model = \Modules\PkgEvaluateurs\Models\EvaluationRealisationProjet::find($id);
        if (!$model) {
            return;
        }

        /** @var \Modules\PkgEvaluateurs\Services\EvaluationRealisationProjetService $service */
        $service = app(\Modules\PkgEvaluateurs\Services\EvaluationRealisationProjetService::class);
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
