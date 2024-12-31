<?php
// Ce fichier est maintenu par ESSARRAJ Fouad



namespace Modules\Core\App\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SysModelRequest extends FormRequest
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
            'module_id' => 'required',
            'color_id' => 'required'
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
            'module_id.required' => __('validation.required', ['attribute' => __('Core::SysModel.module_id')]),
            'module_id.max' => __('validation.module_idMax'),
            'color_id.required' => __('validation.required', ['attribute' => __('Core::SysModel.color_id')]),
            'color_id.max' => __('validation.color_idMax')
        ];
    }
}
