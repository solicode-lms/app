<?php
// Ce fichier est maintenu par ESSARRAJ Fouad



namespace Modules\PkgApprentissage\App\Requests\Base;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Modules\PkgApprentissage\Models\RealisationChapitre;

class BaseRealisationChapitreRequest extends FormRequest
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
            'chapitre_id' => 'required',
            'etat_realisation_chapitre_id' => 'nullable',
            'date_debut' => 'nullable',
            'date_fin' => 'nullable',
            'realisation_ua_id' => 'required',
            'realisation_tache_id' => 'nullable',
            'commentaire_formateur' => 'nullable|string'
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
            'chapitre_id.required' => __('validation.required', ['attribute' => __('PkgApprentissage::RealisationChapitre.chapitre_id')]),
            'etat_realisation_chapitre_id.required' => __('validation.required', ['attribute' => __('PkgApprentissage::RealisationChapitre.etat_realisation_chapitre_id')]),
            'date_debut.required' => __('validation.required', ['attribute' => __('PkgApprentissage::RealisationChapitre.date_debut')]),
            'date_fin.required' => __('validation.required', ['attribute' => __('PkgApprentissage::RealisationChapitre.date_fin')]),
            'realisation_ua_id.required' => __('validation.required', ['attribute' => __('PkgApprentissage::RealisationChapitre.realisation_ua_id')]),
            'realisation_tache_id.required' => __('validation.required', ['attribute' => __('PkgApprentissage::RealisationChapitre.realisation_tache_id')]),
            'commentaire_formateur.required' => __('validation.required', ['attribute' => __('PkgApprentissage::RealisationChapitre.commentaire_formateur')])
        ];
    }

    
    protected function prepareForValidation()
    {
        $user = Auth::user();

        // Définition des rôles autorisés pour chaque champ
        $editableFieldsByRoles = [
            
            'chapitre_id' => "root",
            
            'apprenant' => "root",
            
            'date_debut' => "root",
            
            'date_fin' => "root",
            
            'realisation_ua_id' => "root",
            
            'realisation_tache_id' => "root",
            
            'commentaire_formateur' => "formateur",
            
        ];

        // Charger l'instance actuelle du modèle (optionnel, selon ton contexte)
        $realisation_chapitre_id = $this->route('realisationChapitre'); // Remplace 'model' par le bon paramètre de route
        
        // Vérifier si c'est une édition (realisationChapitre existant dans l'URL)
        if (!$realisation_chapitre_id) {
            return;
        }
        
        $model = RealisationChapitre::find($realisation_chapitre_id);

        
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
