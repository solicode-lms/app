<?php
// Ce fichier est maintenu par ESSARRAJ Fouad



namespace Modules\PkgValidationProjets\App\Requests\Base;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Modules\PkgValidationProjets\Models\EvaluationRealisationTache;

class BaseEvaluationRealisationTacheRequest extends FormRequest
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
            'realisation_tache_id' => 'required',
            'evaluateur_id' => 'required',
            'note' => 'required',
            'message' => 'nullable|string',
            'evaluation_realisation_projet_id' => 'nullable'
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
            'realisation_tache_id.required' => __('validation.required', ['attribute' => __('PkgValidationProjets::EvaluationRealisationTache.realisation_tache_id')]),
            'evaluateur_id.required' => __('validation.required', ['attribute' => __('PkgValidationProjets::EvaluationRealisationTache.evaluateur_id')]),
            'note.required' => __('validation.required', ['attribute' => __('PkgValidationProjets::EvaluationRealisationTache.note')]),
            'message.required' => __('validation.required', ['attribute' => __('PkgValidationProjets::EvaluationRealisationTache.message')]),
            'evaluation_realisation_projet_id.required' => __('validation.required', ['attribute' => __('PkgValidationProjets::EvaluationRealisationTache.evaluation_realisation_projet_id')])
        ];
    }

    
    protected function prepareForValidation()
    {
        $user = Auth::user();

        // Définition des rôles autorisés pour chaque champ
        $editableFieldsByRoles = [
            
            'realisation_tache_id' => "admin",
            
            'evaluateur_id' => "admin",
            
            'evaluation_realisation_projet_id' => "admin",
            
        ];

        // Charger l'instance actuelle du modèle (optionnel, selon ton contexte)
        $evaluation_realisation_tache_id = $this->route('evaluationRealisationTache'); // Remplace 'model' par le bon paramètre de route
        
        // Vérifier si c'est une édition (evaluationRealisationTache existant dans l'URL)
        if (!$evaluation_realisation_tache_id) {
            return;
        }
        
        $model = EvaluationRealisationTache::find($evaluation_realisation_tache_id);

        
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
