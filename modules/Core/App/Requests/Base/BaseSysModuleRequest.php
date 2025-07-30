<?php
// Ce fichier est maintenu par ESSARRAJ Fouad



namespace Modules\Core\App\Requests\Base;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Modules\Core\Models\SysModule;

class BaseSysModuleRequest extends FormRequest
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
            'ordre' => 'required|integer',
            'name' => 'required|string|max:255',
            'slug' => 'required|string|max:255',
            'description' => 'nullable|string',
            'is_active' => 'required|integer',
            'version' => 'required|string|max:255',
            'sys_color_id' => 'required'
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
            'ordre.required' => __('validation.required', ['attribute' => __('Core::SysModule.ordre')]),
            'name.required' => __('validation.required', ['attribute' => __('Core::SysModule.name')]),
            'name.max' => __('validation.nameMax'),
            'slug.required' => __('validation.required', ['attribute' => __('Core::SysModule.slug')]),
            'slug.max' => __('validation.slugMax'),
            'description.required' => __('validation.required', ['attribute' => __('Core::SysModule.description')]),
            'is_active.required' => __('validation.required', ['attribute' => __('Core::SysModule.is_active')]),
            'version.required' => __('validation.required', ['attribute' => __('Core::SysModule.version')]),
            'version.max' => __('validation.versionMax'),
            'sys_color_id.required' => __('validation.required', ['attribute' => __('Core::SysModule.sys_color_id')])
        ];
    }

    
}
