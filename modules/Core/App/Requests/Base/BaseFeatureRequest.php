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
            'description' => 'nullable',
            'domain_id' => 'required',
            'name' => 'required|max:255'
        ];
    }

    public function messages(): array
    {
        return [
            'description.required' => __('validation.required', ['attribute' => __('Core::Feature.description')]),
            'description.max' => __('validation.descriptionMax'),
            'domain_id.required' => __('validation.required', ['attribute' => __('Core::Feature.domain_id')]),
            'domain_id.max' => __('validation.domain_idMax'),
            'name.required' => __('validation.required', ['attribute' => __('Core::Feature.name')]),
            'name.max' => __('validation.nameMax')
        ];
    }
}
