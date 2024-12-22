<?php
// Ce fichier est maintenu par ESSARRAJ Fouad



namespace Modules\Core\App\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SysModuleRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => 'required|max:255',
            'slug' => 'required|max:255',
            'description' => 'required|max:255',
            'is_active' => 'required',
            'order' => 'required',
            'version' => 'required|max:255'
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => __('validation.required', ['attribute' => __('PkgBlog::category.name')]),
            'name.max' => __('validation.nameMax'),
            'slug.required' => __('validation.required', ['attribute' => __('PkgBlog::category.slug')]),
            'slug.max' => __('validation.slugMax'),
            'description.required' => __('validation.required', ['attribute' => __('PkgBlog::category.description')]),
            'description.max' => __('validation.descriptionMax'),
            'is_active.required' => __('validation.required', ['attribute' => __('PkgBlog::category.is_active')]),
            'is_active.max' => __('validation.is_activeMax'),
            'order.required' => __('validation.required', ['attribute' => __('PkgBlog::category.order')]),
            'order.max' => __('validation.orderMax'),
            'version.required' => __('validation.required', ['attribute' => __('PkgBlog::category.version')]),
            'version.max' => __('validation.versionMax')
        ];
    }
}
