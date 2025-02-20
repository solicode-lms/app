<?php
// Ce fichier est maintenu par ESSARRAJ Fouad



namespace Modules\Core\App\Requests\Base;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Modules\Core\Models\SysModel;

class BaseSysModelRequest extends FormRequest
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
            'name' => 'required|string|max:255',
            'model' => 'required|string|max:255',
            'description' => 'nullable|string',
            'sys_module_id' => 'required',
            'sys_color_id' => 'nullable'
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
            'name.required' => __('validation.required', ['attribute' => __('Core::SysModel.name')]),
            'name.max' => __('validation.nameMax'),
            'model.required' => __('validation.required', ['attribute' => __('Core::SysModel.model')]),
            'model.max' => __('validation.modelMax'),
            'description.required' => __('validation.required', ['attribute' => __('Core::SysModel.description')]),
            'sys_module_id.required' => __('validation.required', ['attribute' => __('Core::SysModel.sys_module_id')]),
            'sys_color_id.required' => __('validation.required', ['attribute' => __('Core::SysModel.sys_color_id')])
        ];
    }

    
}
