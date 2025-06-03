<?php
// Ce fichier est maintenu par ESSARRAJ Fouad



namespace Modules\PkgValidationProjets\App\Requests\Base;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Modules\PkgValidationProjets\Models\EvaluationRealisationProjet;

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
            'date_evaluation' => 'required',
            'remarques' => 'nullable|string',
            'realisation_projet_id' => 'required',
            'evaluateur_id' => 'required',
            'etat_evaluation_projet_id' => 'nullable'
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
            'date_evaluation.required' => __('validation.required', ['attribute' => __('PkgValidationProjets::EvaluationRealisationProjet.date_evaluation')]),
            'remarques.required' => __('validation.required', ['attribute' => __('PkgValidationProjets::EvaluationRealisationProjet.remarques')]),
            'realisation_projet_id.required' => __('validation.required', ['attribute' => __('PkgValidationProjets::EvaluationRealisationProjet.realisation_projet_id')]),
            'evaluateur_id.required' => __('validation.required', ['attribute' => __('PkgValidationProjets::EvaluationRealisationProjet.evaluateur_id')]),
            'etat_evaluation_projet_id.required' => __('validation.required', ['attribute' => __('PkgValidationProjets::EvaluationRealisationProjet.etat_evaluation_projet_id')])
        ];
    }

    
}
