<?php
// Ce fichier est maintenu par ESSARRAJ Fouad



namespace Modules\Core\App\Requests\Base;

use Illuminate\Foundation\Http\FormRequest;

class BaseSysModelRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => 'required|max:255',
            'model' => 'required|max:255',
            'description' => 'nullable',
            'sys_module_id' => 'required',
            'sys_color_id' => 'nullable'
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => __('validation.required', ['attribute' => __('Core::SysModel.name')]),
            'name.max' => __('validation.nameMax'),
            'model.required' => __('validation.required', ['attribute' => __('Core::SysModel.model')]),
            'model.max' => __('validation.modelMax'),
            'description.required' => __('validation.required', ['attribute' => __('Core::SysModel.description')]),
            'description.max' => __('validation.descriptionMax'),
            'sys_module_id.required' => __('validation.required', ['attribute' => __('Core::SysModel.sys_module_id')]),
            'sys_module_id.max' => __('validation.sys_module_idMax'),
            'sys_color_id.required' => __('validation.required', ['attribute' => __('Core::SysModel.sys_color_id')]),
            'sys_color_id.max' => __('validation.sys_color_idMax')
        ];
    }
}
