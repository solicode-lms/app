<?php
// Ce fichier est maintenu par ESSARRAJ Fouad



namespace Modules\Core\App\Requests\Base;

use Illuminate\Foundation\Http\FormRequest;

class BaseSysModuleRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'color_id' => 'required',
            'description' => 'nullable',
            'is_active' => 'required',
            'name' => 'required|max:255',
            'order' => 'required',
            'slug' => 'required|max:255',
            'version' => 'required|max:255'
        ];
    }

    public function messages(): array
    {
        return [
            'color_id.required' => __('validation.required', ['attribute' => __('Core::SysModule.color_id')]),
            'color_id.max' => __('validation.color_idMax'),
            'description.required' => __('validation.required', ['attribute' => __('Core::SysModule.description')]),
            'description.max' => __('validation.descriptionMax'),
            'is_active.required' => __('validation.required', ['attribute' => __('Core::SysModule.is_active')]),
            'is_active.max' => __('validation.is_activeMax'),
            'name.required' => __('validation.required', ['attribute' => __('Core::SysModule.name')]),
            'name.max' => __('validation.nameMax'),
            'order.required' => __('validation.required', ['attribute' => __('Core::SysModule.order')]),
            'order.max' => __('validation.orderMax'),
            'slug.required' => __('validation.required', ['attribute' => __('Core::SysModule.slug')]),
            'slug.max' => __('validation.slugMax'),
            'version.required' => __('validation.required', ['attribute' => __('Core::SysModule.version')]),
            'version.max' => __('validation.versionMax')
        ];
    }
}
