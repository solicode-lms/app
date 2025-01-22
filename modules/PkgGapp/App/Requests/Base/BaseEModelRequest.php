<?php
// Ce fichier est maintenu par ESSARRAJ Fouad



namespace Modules\PkgGapp\App\Requests\Base;

use Illuminate\Foundation\Http\FormRequest;

class BaseEModelRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'code' => 'required|max:255',
            'name' => 'required|max:255',
            'table_name' => 'required|max:255',
            'icon' => 'nullable|max:255',
            'is_pivot_table' => 'required',
            'description' => 'nullable',
            'e_package_code' => 'required|max:255',
            'e_package_id' => 'required'
        ];
    }

    public function messages(): array
    {
        return [
            'code.required' => __('validation.required', ['attribute' => __('PkgGapp::EModel.code')]),
            'code.max' => __('validation.codeMax'),
            'name.required' => __('validation.required', ['attribute' => __('PkgGapp::EModel.name')]),
            'name.max' => __('validation.nameMax'),
            'table_name.required' => __('validation.required', ['attribute' => __('PkgGapp::EModel.table_name')]),
            'table_name.max' => __('validation.table_nameMax'),
            'icon.required' => __('validation.required', ['attribute' => __('PkgGapp::EModel.icon')]),
            'icon.max' => __('validation.iconMax'),
            'is_pivot_table.required' => __('validation.required', ['attribute' => __('PkgGapp::EModel.is_pivot_table')]),
            'is_pivot_table.max' => __('validation.is_pivot_tableMax'),
            'description.required' => __('validation.required', ['attribute' => __('PkgGapp::EModel.description')]),
            'description.max' => __('validation.descriptionMax'),
            'e_package_code.required' => __('validation.required', ['attribute' => __('PkgGapp::EModel.e_package_code')]),
            'e_package_code.max' => __('validation.e_package_codeMax'),
            'e_package_id.required' => __('validation.required', ['attribute' => __('PkgGapp::EModel.e_package_id')]),
            'e_package_id.max' => __('validation.e_package_idMax')
        ];
    }
}
