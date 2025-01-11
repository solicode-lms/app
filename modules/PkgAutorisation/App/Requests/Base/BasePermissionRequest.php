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
            'controller_id' => 'nullable',
            'guard_name' => 'required|max:255',
            'name' => 'required|max:255'
        ];
    }

    public function messages(): array
    {
        return [
            'controller_id.required' => __('validation.required', ['attribute' => __('PkgAutorisation::Permission.controller_id')]),
            'controller_id.max' => __('validation.controller_idMax'),
            'guard_name.required' => __('validation.required', ['attribute' => __('PkgAutorisation::Permission.guard_name')]),
            'guard_name.max' => __('validation.guard_nameMax'),
            'name.required' => __('validation.required', ['attribute' => __('PkgAutorisation::Permission.name')]),
            'name.max' => __('validation.nameMax')
        ];
    }
}
