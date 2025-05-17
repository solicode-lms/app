<?php
// Ce fichier est maintenu par ESSARRAJ Fouad



namespace Modules\PkgRealisationProjets\App\Requests\Base;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Modules\PkgRealisationProjets\Models\AffectationProjet;

class BaseAffectationProjetRequest extends FormRequest
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
            'projet_id' => 'required',
            'groupe_id' => 'required',
            'annee_formation_id' => 'required',
            'date_debut' => 'required',
            'date_fin' => 'nullable',
            'is_formateur_evaluateur' => 'required|boolean',
            'evaluateurs' => 'nullable|array',
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
            'projet_id.required' => __('validation.required', ['attribute' => __('PkgRealisationProjets::AffectationProjet.projet_id')]),
            'groupe_id.required' => __('validation.required', ['attribute' => __('PkgRealisationProjets::AffectationProjet.groupe_id')]),
            'annee_formation_id.required' => __('validation.required', ['attribute' => __('PkgRealisationProjets::AffectationProjet.annee_formation_id')]),
            'date_debut.required' => __('validation.required', ['attribute' => __('PkgRealisationProjets::AffectationProjet.date_debut')]),
            'date_fin.required' => __('validation.required', ['attribute' => __('PkgRealisationProjets::AffectationProjet.date_fin')]),
            'is_formateur_evaluateur.required' => __('validation.required', ['attribute' => __('PkgRealisationProjets::AffectationProjet.is_formateur_evaluateur')]),
            'evaluateurs.required' => __('validation.required', ['attribute' => __('PkgRealisationProjets::AffectationProjet.evaluateurs')]),
            'evaluateurs.array' => __('validation.array', ['attribute' => __('PkgRealisationProjets::AffectationProjet.evaluateurs')]),
            'description.required' => __('validation.required', ['attribute' => __('PkgRealisationProjets::AffectationProjet.description')])
        ];
    }

    
    protected function prepareForValidation()
    {
        $user = Auth::user();

        // Définition des rôles autorisés pour chaque champ
        $editableFieldsByRoles = [
            
            'projet_id' => "admin,formateur",
            
            'groupe_id' => "formateur,admin",
            
        ];

        // Charger l'instance actuelle du modèle (optionnel, selon ton contexte)
        $affectation_projet_id = $this->route('affectationProjet'); // Remplace 'model' par le bon paramètre de route
        
        // Vérifier si c'est une édition (affectationProjet existant dans l'URL)
        if (!$affectation_projet_id) {
            return;
        }
        
        $model = AffectationProjet::find($affectation_projet_id);

        
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
