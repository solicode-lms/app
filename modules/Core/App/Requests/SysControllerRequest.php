<?php
// Ce fichier est maintenu par ESSARRAJ Fouad



namespace Modules\Core\App\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SysControllerRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'module_id' => 'required',
            'name' => 'required|max:255',
            'slug' => 'required|max:255',
            'description' => 'nullable|max:255',
            'is_active' => 'required'
        ];
    }

    public function messages(): array
    {
        return [
            'module_id.required' => __('validation.required', ['attribute' => __('Core::SysController.module_id')]),
            'module_id.max' => __('validation.module_idMax'),
            'name.required' => __('validation.required', ['attribute' => __('Core::SysController.name')]),
            'name.max' => __('validation.nameMax'),
            'slug.required' => __('validation.required', ['attribute' => __('Core::SysController.slug')]),
            'slug.max' => __('validation.slugMax'),
            'description.required' => __('validation.required', ['attribute' => __('Core::SysController.description')]),
            'description.max' => __('validation.descriptionMax'),
            'is_active.required' => __('validation.required', ['attribute' => __('Core::SysController.is_active')]),
            'is_active.max' => __('validation.is_activeMax')
        ];
    }
}
