<?php
// Ce fichier est maintenu par ESSARRAJ Fouad



namespace Modules\PkgWidgets\App\Requests;

use Illuminate\Foundation\Http\FormRequest;

class WidgetRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => 'required|max:255',
            'type_id' => 'required',
            'model_id' => 'required',
            'operation_id' => 'required',
            'color' => 'required|max:255',
            'icon' => 'required|max:255',
            'label' => 'required|max:255',
            'parameters' => 'required'
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => __('validation.required', ['attribute' => __('PkgBlog::category.name')]),
            'name.max' => __('validation.nameMax'),
            'type_id.required' => __('validation.required', ['attribute' => __('PkgBlog::category.type_id')]),
            'type_id.max' => __('validation.type_idMax'),
            'model_id.required' => __('validation.required', ['attribute' => __('PkgBlog::category.model_id')]),
            'model_id.max' => __('validation.model_idMax'),
            'operation_id.required' => __('validation.required', ['attribute' => __('PkgBlog::category.operation_id')]),
            'operation_id.max' => __('validation.operation_idMax'),
            'color.required' => __('validation.required', ['attribute' => __('PkgBlog::category.color')]),
            'color.max' => __('validation.colorMax'),
            'icon.required' => __('validation.required', ['attribute' => __('PkgBlog::category.icon')]),
            'icon.max' => __('validation.iconMax'),
            'label.required' => __('validation.required', ['attribute' => __('PkgBlog::category.label')]),
            'label.max' => __('validation.labelMax'),
            'parameters.required' => __('validation.required', ['attribute' => __('PkgBlog::category.parameters')]),
            'parameters.max' => __('validation.parametersMax')
        ];
    }
}
