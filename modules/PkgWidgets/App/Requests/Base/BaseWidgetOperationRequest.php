<?php
// Ce fichier est maintenu par ESSARRAJ Fouad



namespace Modules\PkgWidgets\App\Requests\Base;

use Illuminate\Foundation\Http\FormRequest;

class BaseWidgetOperationRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'description' => 'nullable',
            'operation' => 'required|max:255'
        ];
    }

    public function messages(): array
    {
        return [
            'description.required' => __('validation.required', ['attribute' => __('PkgWidgets::WidgetOperation.description')]),
            'description.max' => __('validation.descriptionMax'),
            'operation.required' => __('validation.required', ['attribute' => __('PkgWidgets::WidgetOperation.operation')]),
            'operation.max' => __('validation.operationMax')
        ];
    }
}
