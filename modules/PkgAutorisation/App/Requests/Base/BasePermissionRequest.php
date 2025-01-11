<?php
// Ce fichier est maintenu par ESSARRAJ Fouad



namespace Modules\PkgAutorisation\App\Requests\Base;

use Illuminate\Foundation\Http\FormRequest;

class BasePermissionRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => 'required|max:255',
            'guard_name' => 'required|max:255',
            'controller_id' => 'nullable'
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => __('validation.required', ['attribute' => __('PkgAutorisation::Permission.name')]),
            'name.max' => __('validation.nameMax'),
            'guard_name.required' => __('validation.required', ['attribute' => __('PkgAutorisation::Permission.guard_name')]),
            'guard_name.max' => __('validation.guard_nameMax'),
            'controller_id.required' => __('validation.required', ['attribute' => __('PkgAutorisation::Permission.controller_id')]),
            'controller_id.max' => __('validation.controller_idMax')
        ];
    }
}
