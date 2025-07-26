<?php
// Ce fichier est maintenu par ESSARRAJ Fouad



namespace Modules\PkgCreationProjet\App\Requests\Base;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Modules\PkgCreationProjet\Models\Projet;

class BaseProjetRequest extends FormRequest
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
            'titre' => 'required|string|max:255',
            'travail_a_faire' => 'required|string',
            'critere_de_travail' => 'required|string',
            'filiere_id' => 'required',
            'formateur_id' => 'required',
            'description' => 'nullable|string',
            'session_formation_id' => 'nullable'
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
            'titre.required' => __('validation.required', ['attribute' => __('PkgCreationProjet::Projet.titre')]),
            'titre.max' => __('validation.titreMax'),
            'travail_a_faire.required' => __('validation.required', ['attribute' => __('PkgCreationProjet::Projet.travail_a_faire')]),
            'critere_de_travail.required' => __('validation.required', ['attribute' => __('PkgCreationProjet::Projet.critere_de_travail')]),
            'filiere_id.required' => __('validation.required', ['attribute' => __('PkgCreationProjet::Projet.filiere_id')]),
            'formateur_id.required' => __('validation.required', ['attribute' => __('PkgCreationProjet::Projet.formateur_id')]),
            'description.required' => __('validation.required', ['attribute' => __('PkgCreationProjet::Projet.description')]),
            'session_formation_id.required' => __('validation.required', ['attribute' => __('PkgCreationProjet::Projet.session_formation_id')])
        ];
    }

    
    protected function prepareForValidation()
    {
        $user = Auth::user();

        // Définition des rôles autorisés pour chaque champ
        $editableFieldsByRoles = [
            
            'formateur_id' => "admin",
            
        ];

        // Charger l'instance actuelle du modèle (optionnel, selon ton contexte)
        $projet_id = $this->route('projet'); // Remplace 'model' par le bon paramètre de route
        
        // Vérifier si c'est une édition (projet existant dans l'URL)
        if (!$projet_id) {
            return;
        }
        
        $model = Projet::find($projet_id);

        
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
