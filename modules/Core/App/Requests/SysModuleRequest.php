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
            'description' => 'nullable',
            'is_active' => 'required',
            'order' => 'required',
            'version' => 'required|max:255',
            'color_id' => 'required'
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => __('validation.required', ['attribute' => __('Core::SysModule.name')]),
            'name.max' => __('validation.nameMax'),
            'slug.required' => __('validation.required', ['attribute' => __('Core::SysModule.slug')]),
            'slug.max' => __('validation.slugMax'),
            'description.required' => __('validation.required', ['attribute' => __('Core::SysModule.description')]),
            'description.max' => __('validation.descriptionMax'),
            'is_active.required' => __('validation.required', ['attribute' => __('Core::SysModule.is_active')]),
            'is_active.max' => __('validation.is_activeMax'),
            'order.required' => __('validation.required', ['attribute' => __('Core::SysModule.order')]),
            'order.max' => __('validation.orderMax'),
            'version.required' => __('validation.required', ['attribute' => __('Core::SysModule.version')]),
            'version.max' => __('validation.versionMax'),
            'color_id.required' => __('validation.required', ['attribute' => __('Core::SysModule.color_id')]),
            'color_id.max' => __('validation.color_idMax')
        ];
    }
}
