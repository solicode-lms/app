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
            'etats_realisation_projet_id' => 'nullable',
            'date_debut' => 'required',
            'date_fin' => 'nullable',
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
            'date_debut.required' => __('validation.required', ['attribute' => __('PkgRealisationProjets::RealisationProjet.date_debut')]),
            'date_fin.required' => __('validation.required', ['attribute' => __('PkgRealisationProjets::RealisationProjet.date_fin')]),
            'rapport.required' => __('validation.required', ['attribute' => __('PkgRealisationProjets::RealisationProjet.rapport')])
        ];
    }

    
    protected function prepareForValidation()
    {
        $user = Auth::user();

        // Définition des rôles autorisés pour chaque champ
        $editableFieldsByRoles = [
            
            'affectation_projet_id' => "formateur",
            
            'apprenant_id' => "formateur",
            
        ];

        // Charger l'instance actuelle du modèle (optionnel, selon ton contexte)
        $realisation_projet_id = $this->route('realisationProjet'); // Remplace 'model' par le bon paramètre de route
        
        // Vérifier si c'est une édition (realisationProjet existant dans l'URL)
        if (!$realisation_projet_id) {
            return;
        }
        
        $model = RealisationProjet::find($realisation_projet_id);

        
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
