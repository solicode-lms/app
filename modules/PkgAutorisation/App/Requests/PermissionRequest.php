<?php
// Ce fichier est maintenu par ESSARRAJ Fouad



namespace Modules\PkgAutorisation\App\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PermissionRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => 'required|max:255',
            'guard_name' => 'required|max:255',
            'controller_id' => 'nullable'
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => __('validation.required', ['attribute' => __('PkgBlog::category.name')]),
            'name.max' => __('validation.nameMax'),
            'guard_name.required' => __('validation.required', ['attribute' => __('PkgBlog::category.guard_name')]),
            'guard_name.max' => __('validation.guard_nameMax'),
            'controller_id.required' => __('validation.required', ['attribute' => __('PkgBlog::category.controller_id')]),
            'controller_id.max' => __('validation.controller_idMax')
        ];
    }
}
