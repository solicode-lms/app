<?php
// Ce fichier est maintenu par ESSARRAJ Fouad



namespace Modules\PkgAutoformation\App\Requests\Base;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Modules\PkgAutoformation\Models\Formation;

class BaseFormationRequest extends FormRequest
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
            'nom' => 'required|string|max:255',
            'lien' => 'nullable|string|max:255',
            'competence_id' => 'nullable',
            'technologies' => 'nullable|array',
            'is_officiel' => 'required|boolean',
            'formateur_id' => 'nullable',
            'formation_officiel_id' => 'nullable',
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
            'nom.required' => __('validation.required', ['attribute' => __('PkgAutoformation::Formation.nom')]),
            'nom.max' => __('validation.nomMax'),
            'lien.required' => __('validation.required', ['attribute' => __('PkgAutoformation::Formation.lien')]),
            'lien.max' => __('validation.lienMax'),
            'competence_id.required' => __('validation.required', ['attribute' => __('PkgAutoformation::Formation.competence_id')]),
            'technologies.required' => __('validation.required', ['attribute' => __('PkgAutoformation::Formation.technologies')]),
            'technologies.array' => __('validation.array', ['attribute' => __('PkgAutoformation::Formation.technologies')]),
            'is_officiel.required' => __('validation.required', ['attribute' => __('PkgAutoformation::Formation.is_officiel')]),
            'formateur_id.required' => __('validation.required', ['attribute' => __('PkgAutoformation::Formation.formateur_id')]),
            'formation_officiel_id.required' => __('validation.required', ['attribute' => __('PkgAutoformation::Formation.formation_officiel_id')]),
            'description.required' => __('validation.required', ['attribute' => __('PkgAutoformation::Formation.description')])
        ];
    }

    
    protected function prepareForValidation()
    {
        $user = Auth::user();

        // Définition des rôles autorisés pour chaque champ
        $editableFieldsByRoles = [
            
            'is_officiel' => "admin,admin-formateur",
            
        ];

        // Charger l'instance actuelle du modèle (optionnel, selon ton contexte)
        $formation_id = $this->route('formation'); // Remplace 'model' par le bon paramètre de route
        
        // Vérifier si c'est une édition (formation existant dans l'URL)
        if (!$formation_id) {
            return;
        }
        
        $model = Formation::find($formation_id);

        
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
