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
            'model' => 'required|max:255',
            'description' => 'required|max:255'
        ];
    }

    public function messages(): array
    {
        return [
            'model.required' => __('validation.required', ['attribute' => __('PkgBlog::category.model')]),
            'model.max' => __('validation.modelMax'),
            'description.required' => __('validation.required', ['attribute' => __('PkgBlog::category.description')]),
            'description.max' => __('validation.descriptionMax')
        ];
    }
}