<?php
// Ce fichier est maintenu par ESSARRAJ Fouad



namespace Modules\PkgWidgets\App\Requests;

use Illuminate\Foundation\Http\FormRequest;

class WidgetOperationRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'operation' => 'required|max:255',
            'description' => 'nullable'
        ];
    }

    public function messages(): array
    {
        return [
            'operation.required' => __('validation.required', ['attribute' => __('PkgWidgets::WidgetOperation.operation')]),
            'operation.max' => __('validation.operationMax'),
            'description.required' => __('validation.required', ['attribute' => __('PkgWidgets::WidgetOperation.description')]),
            'description.max' => __('validation.descriptionMax')
        ];
    }
}
