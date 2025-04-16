<?php
// Ce fichier est maintenu par ESSARRAJ Fouad



namespace Modules\Core\App\Requests\Base;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Modules\Core\Models\UserModelFilter;

class BaseUserModelFilterRequest extends FormRequest
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
            'model_name' => 'required|string|max:255',
            'filters' => 'nullable'
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
            'user_id.required' => __('validation.required', ['attribute' => __('Core::UserModelFilter.user_id')]),
            'model_name.required' => __('validation.required', ['attribute' => __('Core::UserModelFilter.model_name')]),
            'model_name.max' => __('validation.model_nameMax'),
            'filters.required' => __('validation.required', ['attribute' => __('Core::UserModelFilter.filters')])
        ];
    }

    
}
