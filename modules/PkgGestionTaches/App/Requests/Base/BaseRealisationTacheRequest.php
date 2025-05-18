<?php
// Ce fichier est maintenu par ESSARRAJ Fouad



namespace Modules\PkgGestionTaches\App\Requests\Base;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Modules\PkgGestionTaches\Models\RealisationTache;

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
            'realisation_projet_id' => 'required',
            'dateDebut' => 'required',
            'dateFin' => 'nullable',
            'remarque_evaluateur' => 'nullable|string',
            'etat_realisation_tache_id' => 'nullable',
            'note' => 'nullable',
            'remarques_formateur' => 'nullable|string',
            'remarques_apprenant' => 'nullable|string'
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
            'tache_id.required' => __('validation.required', ['attribute' => __('PkgGestionTaches::RealisationTache.tache_id')]),
            'realisation_projet_id.required' => __('validation.required', ['attribute' => __('PkgGestionTaches::RealisationTache.realisation_projet_id')]),
            'dateDebut.required' => __('validation.required', ['attribute' => __('PkgGestionTaches::RealisationTache.dateDebut')]),
            'dateFin.required' => __('validation.required', ['attribute' => __('PkgGestionTaches::RealisationTache.dateFin')]),
            'remarque_evaluateur.required' => __('validation.required', ['attribute' => __('PkgGestionTaches::RealisationTache.remarque_evaluateur')]),
            'etat_realisation_tache_id.required' => __('validation.required', ['attribute' => __('PkgGestionTaches::RealisationTache.etat_realisation_tache_id')]),
            'note.required' => __('validation.required', ['attribute' => __('PkgGestionTaches::RealisationTache.note')]),
            'remarques_formateur.required' => __('validation.required', ['attribute' => __('PkgGestionTaches::RealisationTache.remarques_formateur')]),
            'remarques_apprenant.required' => __('validation.required', ['attribute' => __('PkgGestionTaches::RealisationTache.remarques_apprenant')])
        ];
    }

    
    protected function prepareForValidation()
    {
        $user = Auth::user();

        // Définition des rôles autorisés pour chaque champ
        $editableFieldsByRoles = [
            
            'tache_id' => "formateur,admin",
            
            'realisation_projet_id' => "formateur,admin",
            
            'dateDebut' => "apprenant,formateur,admin",
            
            'dateFin' => "apprenant,formateur,admin",
            
            'etat_realisation_tache_id' => "apprenant,formateur,admin",
            
            'note' => "formateur,evaluateur",
            
            'remarques_formateur' => "formateur",
            
            'remarques_apprenant' => "apprenant,formateur,admin",
            
        ];

        // Charger l'instance actuelle du modèle (optionnel, selon ton contexte)
        $realisation_tache_id = $this->route('realisationTache'); // Remplace 'model' par le bon paramètre de route
        
        // Vérifier si c'est une édition (realisationTache existant dans l'URL)
        if (!$realisation_tache_id) {
            return;
        }
        
        $model = RealisationTache::find($realisation_tache_id);

        
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
