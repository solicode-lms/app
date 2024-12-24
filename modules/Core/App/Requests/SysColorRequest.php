<?php
// Ce fichier est maintenu par ESSARRAJ Fouad



namespace Modules\Core\App\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SysColorRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => 'required|max:255',
            'hex' => 'required|max:255'
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => __('validation.required', ['attribute' => __('PkgBlog::category.name')]),
            'name.max' => __('validation.nameMax'),
            'hex.required' => __('validation.required', ['attribute' => __('PkgBlog::category.hex')]),
            'hex.max' => __('validation.hexMax')
        ];
    }
}
