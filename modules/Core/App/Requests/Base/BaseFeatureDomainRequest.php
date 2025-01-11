<?php
// Ce fichier est maintenu par ESSARRAJ Fouad



namespace Modules\Core\App\Requests\Base;

use Illuminate\Foundation\Http\FormRequest;

class BaseFeatureDomainRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'description' => 'nullable',
            'module_id' => 'required',
            'name' => 'required|max:255',
            'slug' => 'required|max:255'
        ];
    }

    public function messages(): array
    {
        return [
            'description.required' => __('validation.required', ['attribute' => __('Core::FeatureDomain.description')]),
            'description.max' => __('validation.descriptionMax'),
            'module_id.required' => __('validation.required', ['attribute' => __('Core::FeatureDomain.module_id')]),
            'module_id.max' => __('validation.module_idMax'),
            'name.required' => __('validation.required', ['attribute' => __('Core::FeatureDomain.name')]),
            'name.max' => __('validation.nameMax'),
            'slug.required' => __('validation.required', ['attribute' => __('Core::FeatureDomain.slug')]),
            'slug.max' => __('validation.slugMax')
        ];
    }
}
