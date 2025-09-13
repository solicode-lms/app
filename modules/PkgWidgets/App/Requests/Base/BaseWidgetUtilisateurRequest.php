<?php
// Ce fichier est maintenu par ESSARRAJ Fouad



namespace Modules\PkgWidgets\App\Requests\Base;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Modules\PkgWidgets\Models\WidgetUtilisateur;

class BaseWidgetUtilisateurRequest extends FormRequest
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
            'ordre' => 'nullable|integer',
            'user_id' => 'required',
            'widget_id' => 'required',
            'titre' => 'nullable|string|max:255',
            'sous_titre' => 'nullable|string|max:255',
            'visible' => 'nullable|boolean'
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
            'ordre.required' => __('validation.required', ['attribute' => __('PkgWidgets::WidgetUtilisateur.ordre')]),
            'user_id.required' => __('validation.required', ['attribute' => __('PkgWidgets::WidgetUtilisateur.user_id')]),
            'widget_id.required' => __('validation.required', ['attribute' => __('PkgWidgets::WidgetUtilisateur.widget_id')]),
            'titre.required' => __('validation.required', ['attribute' => __('PkgWidgets::WidgetUtilisateur.titre')]),
            'titre.max' => __('validation.titreMax'),
            'sous_titre.required' => __('validation.required', ['attribute' => __('PkgWidgets::WidgetUtilisateur.sous_titre')]),
            'sous_titre.max' => __('validation.sous_titreMax'),
            'visible.required' => __('validation.required', ['attribute' => __('PkgWidgets::WidgetUtilisateur.visible')])
        ];
    }

    protected function prepareForValidation()
    {

        $user = Auth::user();

        // Définition des rôles autorisés pour chaque champ
        $editableFieldsByRoles = [
            
            'sys_module_id' => "root",
            
            'widget_id' => "root",
            
        ];

        // Charger l'instance actuelle du modèle (optionnel, selon ton contexte)
        $widget_utilisateur_id = $this->route('widgetUtilisateur'); // Remplace 'model' par le bon paramètre de route
        
        // Vérifier si c'est une édition (widgetUtilisateur existant dans l'URL)
        if (!$widget_utilisateur_id) {
            return;
        }
        
        $model = WidgetUtilisateur::find($widget_utilisateur_id);

        
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
