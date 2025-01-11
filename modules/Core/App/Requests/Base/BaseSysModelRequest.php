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
            'color_id' => 'required',
            'description' => 'nullable',
            'model' => 'required|max:255',
            'module_id' => 'required',
            'name' => 'required|max:255'
        ];
    }

    public function messages(): array
    {
        return [
            'color_id.required' => __('validation.required', ['attribute' => __('Core::SysModel.color_id')]),
            'color_id.max' => __('validation.color_idMax'),
            'description.required' => __('validation.required', ['attribute' => __('Core::SysModel.description')]),
            'description.max' => __('validation.descriptionMax'),
            'model.required' => __('validation.required', ['attribute' => __('Core::SysModel.model')]),
            'model.max' => __('validation.modelMax'),
            'module_id.required' => __('validation.required', ['attribute' => __('Core::SysModel.module_id')]),
            'module_id.max' => __('validation.module_idMax'),
            'name.required' => __('validation.required', ['attribute' => __('Core::SysModel.name')]),
            'name.max' => __('validation.nameMax')
        ];
    }
}
