<?php
// Ce fichier est maintenu par ESSARRAJ Fouad



namespace Modules\PkgWidgets\App\Requests;

use Illuminate\Foundation\Http\FormRequest;

class WidgetTypeRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'type' => 'required|max:255',
            'description' => 'nullable|max:255'
        ];
    }

    public function messages(): array
    {
        return [
            'type.required' => __('validation.required', ['attribute' => __('PkgWidgets::WidgetType.type')]),
            'type.max' => __('validation.typeMax'),
            'description.required' => __('validation.required', ['attribute' => __('PkgWidgets::WidgetType.description')]),
            'description.max' => __('validation.descriptionMax')
        ];
    }
}
