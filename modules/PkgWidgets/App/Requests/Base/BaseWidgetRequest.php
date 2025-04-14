<?php
// Ce fichier est maintenu par ESSARRAJ Fouad



namespace Modules\PkgWidgets\App\Requests\Base;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Modules\PkgWidgets\Models\Widget;

class BaseWidgetRequest extends FormRequest
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
            'ordre' => 'nullable|integer',
            'name' => 'required|string|max:255',
            'label' => 'nullable|string|max:255',
            'type_id' => 'required',
            'model_id' => 'required',
            'operation_id' => 'required',
            'color' => 'nullable|string|max:255',
            'icon' => 'nullable|string|max:255',
            'sys_color_id' => 'nullable',
            'roles' => 'nullable|array',
            'section_widget_id' => 'nullable',
            'parameters' => 'nullable'
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
            'ordre.required' => __('validation.required', ['attribute' => __('PkgWidgets::Widget.ordre')]),
            'name.required' => __('validation.required', ['attribute' => __('PkgWidgets::Widget.name')]),
            'name.max' => __('validation.nameMax'),
            'label.required' => __('validation.required', ['attribute' => __('PkgWidgets::Widget.label')]),
            'label.max' => __('validation.labelMax'),
            'type_id.required' => __('validation.required', ['attribute' => __('PkgWidgets::Widget.type_id')]),
            'model_id.required' => __('validation.required', ['attribute' => __('PkgWidgets::Widget.model_id')]),
            'operation_id.required' => __('validation.required', ['attribute' => __('PkgWidgets::Widget.operation_id')]),
            'color.required' => __('validation.required', ['attribute' => __('PkgWidgets::Widget.color')]),
            'color.max' => __('validation.colorMax'),
            'icon.required' => __('validation.required', ['attribute' => __('PkgWidgets::Widget.icon')]),
            'icon.max' => __('validation.iconMax'),
            'sys_color_id.required' => __('validation.required', ['attribute' => __('PkgWidgets::Widget.sys_color_id')]),
            'roles.required' => __('validation.required', ['attribute' => __('PkgWidgets::Widget.roles')]),
            'roles.array' => __('validation.array', ['attribute' => __('PkgWidgets::Widget.roles')]),
            'section_widget_id.required' => __('validation.required', ['attribute' => __('PkgWidgets::Widget.section_widget_id')]),
            'parameters.required' => __('validation.required', ['attribute' => __('PkgWidgets::Widget.parameters')])
        ];
    }

    
}
