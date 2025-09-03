<?php
// Ce fichier est maintenu par ESSARRAJ Fouad



namespace Modules\PkgAutorisation\App\Requests\Base;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Modules\PkgAutorisation\Models\Profile;

class BaseProfileRequest extends FormRequest
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
            'user_id' => 'required',
            'phone' => 'required|string|max:255',
            'address' => 'nullable|string|max:255',
            'profile_picture' => 'nullable|string|max:255',
            'bio' => 'nullable|string'
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
            'user_id.required' => __('validation.required', ['attribute' => __('PkgAutorisation::Profile.user_id')]),
            'phone.required' => __('validation.required', ['attribute' => __('PkgAutorisation::Profile.phone')]),
            'phone.max' => __('validation.phoneMax'),
            'address.required' => __('validation.required', ['attribute' => __('PkgAutorisation::Profile.address')]),
            'address.max' => __('validation.addressMax'),
            'profile_picture.required' => __('validation.required', ['attribute' => __('PkgAutorisation::Profile.profile_picture')]),
            'profile_picture.max' => __('validation.profile_pictureMax'),
            'bio.required' => __('validation.required', ['attribute' => __('PkgAutorisation::Profile.bio')])
        ];
    }

    protected function prepareForValidation()
    {

        $user = Auth::user();

        // Définition des rôles autorisés pour chaque champ
        $editableFieldsByRoles = [
            
            'user_id' => "root",
            
        ];

        // Charger l'instance actuelle du modèle (optionnel, selon ton contexte)
        $profile_id = $this->route('profile'); // Remplace 'model' par le bon paramètre de route
        
        // Vérifier si c'est une édition (profile existant dans l'URL)
        if (!$profile_id) {
            return;
        }
        
        $model = Profile::find($profile_id);

        
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
