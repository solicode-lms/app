<?php
// Ce fichier est maintenu par ESSARRAJ Fouad



namespace Modules\PkgWidgets\App\Requests\Base;

use Illuminate\Foundation\Http\FormRequest;

class BaseWidgetRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'color' => 'nullable|max:255',
            'icon' => 'nullable|max:255',
            'label' => 'nullable|max:255',
            'model_id' => 'required',
            'name' => 'required|max:255',
            'operation_id' => 'required',
            'parameters' => 'nullable',
            'type_id' => 'required'
        ];
    }

    public function messages(): array
    {
        return [
            'color.required' => __('validation.required', ['attribute' => __('PkgWidgets::Widget.color')]),
            'color.max' => __('validation.colorMax'),
            'icon.required' => __('validation.required', ['attribute' => __('PkgWidgets::Widget.icon')]),
            'icon.max' => __('validation.iconMax'),
            'label.required' => __('validation.required', ['attribute' => __('PkgWidgets::Widget.label')]),
            'label.max' => __('validation.labelMax'),
            'model_id.required' => __('validation.required', ['attribute' => __('PkgWidgets::Widget.model_id')]),
            'model_id.max' => __('validation.model_idMax'),
            'name.required' => __('validation.required', ['attribute' => __('PkgWidgets::Widget.name')]),
            'name.max' => __('validation.nameMax'),
            'operation_id.required' => __('validation.required', ['attribute' => __('PkgWidgets::Widget.operation_id')]),
            'operation_id.max' => __('validation.operation_idMax'),
            'parameters.required' => __('validation.required', ['attribute' => __('PkgWidgets::Widget.parameters')]),
            'parameters.max' => __('validation.parametersMax'),
            'type_id.required' => __('validation.required', ['attribute' => __('PkgWidgets::Widget.type_id')]),
            'type_id.max' => __('validation.type_idMax')
        ];
    }
}
