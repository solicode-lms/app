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
            'realisation_projet_id.required' => __('validation.required', ['attribute' => __('PkgValidationProjets::EvaluationRealisationProjet.realisation_projet_id')]),
            'evaluateur_id.required' => __('validation.required', ['attribute' => __('PkgValidationProjets::EvaluationRealisationProjet.evaluateur_id')]),
            'date_evaluation.required' => __('validation.required', ['attribute' => __('PkgValidationProjets::EvaluationRealisationProjet.date_evaluation')]),
            'etat_evaluation_projet_id.required' => __('validation.required', ['attribute' => __('PkgValidationProjets::EvaluationRealisationProjet.etat_evaluation_projet_id')]),
            'remarques.required' => __('validation.required', ['attribute' => __('PkgValidationProjets::EvaluationRealisationProjet.remarques')])
        ];
    }

    
    protected function prepareForValidation()
    {
        $user = Auth::user();

        // Définition des rôles autorisés pour chaque champ
        $editableFieldsByRoles = [
            
            'realisation_projet_id' => "admin",
            
            'evaluateur_id' => "admin",
            
            'date_evaluation' => "admin",
            
            'etat_evaluation_projet_id' => "admin",
            
        ];

        // Charger l'instance actuelle du modèle (optionnel, selon ton contexte)
        $evaluation_realisation_projet_id = $this->route('evaluationRealisationProjet'); // Remplace 'model' par le bon paramètre de route
        
        // Vérifier si c'est une édition (evaluationRealisationProjet existant dans l'URL)
        if (!$evaluation_realisation_projet_id) {
            return;
        }
        
        $model = EvaluationRealisationProjet::find($evaluation_realisation_projet_id);

        
        // Vérification et suppression des champs non autorisés
        foreach ($editableFieldsByRoles as $field => $roles) {
            if (!$user->hasAnyRole(explode(',', $roles))) {
                

                // Supprimer le champ pour éviter l'écrasement
                $this->request->remove($field);

                // Si le champ est absent dans la requête, on garde la valeur actuelle
                $this->merge([$field => $model->$field]);
                
            }
        }
    }
    
}
