<?php
// Ce fichier est maintenu par ESSARRAJ Fouad



namespace Modules\Core\App\Requests;

use Illuminate\Foundation\Http\FormRequest;

class FeatureRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => 'required|max:255',
            'description' => 'nullable|max:255',
            'domain_id' => 'required'
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => __('validation.required', ['attribute' => __('Core::Feature.name')]),
            'name.max' => __('validation.nameMax'),
            'description.required' => __('validation.required', ['attribute' => __('Core::Feature.description')]),
            'description.max' => __('validation.descriptionMax'),
            'domain_id.required' => __('validation.required', ['attribute' => __('Core::Feature.domain_id')]),
            'domain_id.max' => __('validation.domain_idMax')
        ];
    }
}
