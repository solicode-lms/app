<?php
// Ce fichier est maintenu par ESSARRAJ Fouad



namespace Modules\PkgWidgets\App\Requests\Base;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Modules\PkgWidgets\Models\SectionWidget;

class BaseSectionWidgetRequest extends FormRequest
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
            'titre' => 'required|string|max:255',
            'sous_titre' => 'nullable|string|max:255',
            'icone' => 'nullable|string|max:255',
            'ordre' => 'required|integer',
            'sys_color_id' => 'nullable'
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
            'titre.required' => __('validation.required', ['attribute' => __('PkgWidgets::SectionWidget.titre')]),
            'titre.max' => __('validation.titreMax'),
            'sous_titre.required' => __('validation.required', ['attribute' => __('PkgWidgets::SectionWidget.sous_titre')]),
            'sous_titre.max' => __('validation.sous_titreMax'),
            'icone.required' => __('validation.required', ['attribute' => __('PkgWidgets::SectionWidget.icone')]),
            'icone.max' => __('validation.iconeMax'),
            'ordre.required' => __('validation.required', ['attribute' => __('PkgWidgets::SectionWidget.ordre')]),
            'sys_color_id.required' => __('validation.required', ['attribute' => __('PkgWidgets::SectionWidget.sys_color_id')])
        ];
    }

    
}
