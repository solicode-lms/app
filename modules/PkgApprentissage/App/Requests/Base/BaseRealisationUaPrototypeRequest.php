<?php
// Ce fichier est maintenu par ESSARRAJ Fouad



namespace Modules\PkgApprentissage\App\Requests\Base;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Modules\PkgApprentissage\Models\RealisationUaPrototype;

class BaseRealisationUaPrototypeRequest extends FormRequest
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
            'realisation_ua_id' => 'required',
            'bareme' => 'required',
            'note' => 'nullable',
            'remarque_formateur' => 'nullable|string',
            'date_debut' => 'nullable',
            'date_fin' => 'nullable'
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
            'realisation_tache_id.required' => __('validation.required', ['attribute' => __('PkgApprentissage::RealisationUaPrototype.realisation_tache_id')]),
            'realisation_ua_id.required' => __('validation.required', ['attribute' => __('PkgApprentissage::RealisationUaPrototype.realisation_ua_id')]),
            'bareme.required' => __('validation.required', ['attribute' => __('PkgApprentissage::RealisationUaPrototype.bareme')]),
            'note.required' => __('validation.required', ['attribute' => __('PkgApprentissage::RealisationUaPrototype.note')]),
            'remarque_formateur.required' => __('validation.required', ['attribute' => __('PkgApprentissage::RealisationUaPrototype.remarque_formateur')]),
            'date_debut.required' => __('validation.required', ['attribute' => __('PkgApprentissage::RealisationUaPrototype.date_debut')]),
            'date_fin.required' => __('validation.required', ['attribute' => __('PkgApprentissage::RealisationUaPrototype.date_fin')])
        ];
    }

    protected function prepareForValidation()
    {

        $user = Auth::user();

        // Définition des rôles autorisés pour chaque champ
        $editableFieldsByRoles = [
            
            'realisation_tache_id' => "admin",
            
            'realisation_ua_id' => "admin",
            
            'bareme' => "admin",
            
            'date_debut' => "admin",
            
            'date_fin' => "admin",
            
        ];

        // Charger l'instance actuelle du modèle (optionnel, selon ton contexte)
        $realisation_ua_prototype_id = $this->route('realisationUaPrototype'); // Remplace 'model' par le bon paramètre de route
        
        // Vérifier si c'est une édition (realisationUaPrototype existant dans l'URL)
        if (!$realisation_ua_prototype_id) {
            return;
        }
        
        $model = RealisationUaPrototype::find($realisation_ua_prototype_id);

        
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
