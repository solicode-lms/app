<?php
// Ce fichier est maintenu par ESSARRAJ Fouad



namespace Modules\PkgGapp\App\Requests\Base;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Modules\PkgGapp\Models\EModel;

class BaseEModelRequest extends FormRequest
{
    /**
     * Détermine si l'utilisateur est autorisé à effectuer cette requête.
     *
     * @return bool
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Retourne les règles de validation appliquées aux champs de la requête.
     *
     * @return array
     */
    public function rules(): array
    {
        return [
            'icon' => 'nullable|string|max:255',
            'name' => 'required|string|max:255',
            'table_name' => 'required|string|max:255',
            'is_pivot_table' => 'required|boolean',
            'description' => 'nullable|string',
            'e_package_id' => 'required'
        ];
    }

    /**
     * Retourne les messages de validation associés aux règles.
     *
     * @return array
     */
    public function messages(): array
    {
        return [
            'icon.required' => __('validation.required', ['attribute' => __('PkgGapp::EModel.icon')]),
            'name.required' => __('validation.required', ['attribute' => __('PkgGapp::EModel.name')]),
            'name.max' => __('validation.nameMax'),
            'table_name.required' => __('validation.required', ['attribute' => __('PkgGapp::EModel.table_name')]),
            'table_name.max' => __('validation.table_nameMax'),
            'icon.max' => __('validation.iconMax'),
            'is_pivot_table.required' => __('validation.required', ['attribute' => __('PkgGapp::EModel.is_pivot_table')]),
            'description.required' => __('validation.required', ['attribute' => __('PkgGapp::EModel.description')]),
            'e_package_id.required' => __('validation.required', ['attribute' => __('PkgGapp::EModel.e_package_id')])
        ];
    }

    
}
