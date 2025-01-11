<?php
// Ce fichier est maintenu par ESSARRAJ Fouad



namespace Modules\PkgAutorisation\App\Requests\Base;

use Illuminate\Foundation\Http\FormRequest;

class BaseRoleRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'guard_name' => 'required|max:255',
            'name' => 'required|max:255'
        ];
    }

    public function messages(): array
    {
        return [
            'guard_name.required' => __('validation.required', ['attribute' => __('PkgAutorisation::Role.guard_name')]),
            'guard_name.max' => __('validation.guard_nameMax'),
            'name.required' => __('validation.required', ['attribute' => __('PkgAutorisation::Role.name')]),
            'name.max' => __('validation.nameMax')
        ];
    }
}
