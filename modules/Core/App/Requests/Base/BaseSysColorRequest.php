<?php
// Ce fichier est maintenu par ESSARRAJ Fouad



namespace Modules\Core\App\Requests\Base;

use Illuminate\Foundation\Http\FormRequest;

class BaseSysColorRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'hex' => 'required|max:255',
            'name' => 'required|max:255'
        ];
    }

    public function messages(): array
    {
        return [
            'hex.required' => __('validation.required', ['attribute' => __('Core::SysColor.hex')]),
            'hex.max' => __('validation.hexMax'),
            'name.required' => __('validation.required', ['attribute' => __('Core::SysColor.name')]),
            'name.max' => __('validation.nameMax')
        ];
    }
}
