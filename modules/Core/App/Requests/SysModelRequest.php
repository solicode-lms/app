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
            'description' => 'required|max:255',
            'module_id' => 'required',
            'color_id' => 'required'
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => __('validation.required', ['attribute' => __('PkgBlog::category.name')]),
            'name.max' => __('validation.nameMax'),
            'model.required' => __('validation.required', ['attribute' => __('PkgBlog::category.model')]),
            'model.max' => __('validation.modelMax'),
            'description.required' => __('validation.required', ['attribute' => __('PkgBlog::category.description')]),
            'description.max' => __('validation.descriptionMax'),
            'module_id.required' => __('validation.required', ['attribute' => __('PkgBlog::category.module_id')]),
            'module_id.max' => __('validation.module_idMax'),
            'color_id.required' => __('validation.required', ['attribute' => __('PkgBlog::category.color_id')]),
            'color_id.max' => __('validation.color_idMax')
        ];
    }
}
