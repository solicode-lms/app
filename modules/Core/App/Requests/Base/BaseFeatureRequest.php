<?php
// Ce fichier est maintenu par ESSARRAJ Fouad



namespace Modules\Core\App\Requests\Base;

use Illuminate\Foundation\Http\FormRequest;

class BaseFeatureRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => 'required|max:255',
            'description' => 'nullable',
            'feature_domain_id' => 'required'
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => __('validation.required', ['attribute' => __('Core::Feature.name')]),
            'name.max' => __('validation.nameMax'),
            'description.required' => __('validation.required', ['attribute' => __('Core::Feature.description')]),
            'description.max' => __('validation.descriptionMax'),
            'feature_domain_id.required' => __('validation.required', ['attribute' => __('Core::Feature.feature_domain_id')]),
            'feature_domain_id.max' => __('validation.feature_domain_idMax')
        ];
    }
}
