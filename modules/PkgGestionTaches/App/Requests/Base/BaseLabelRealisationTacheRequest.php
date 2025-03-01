<?php
// Ce fichier est maintenu par ESSARRAJ Fouad



namespace Modules\PkgGestionTaches\App\Requests\Base;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Modules\PkgGestionTaches\Models\LabelRealisationTache;

class BaseLabelRealisationTacheRequest extends FormRequest
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
            'nom' => 'required|string|max:255',
            'description' => 'nullable|string',
            'formateur_id' => 'required',
            'sys_color_id' => 'required'
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
            'nom.required' => __('validation.required', ['attribute' => __('PkgGestionTaches::LabelRealisationTache.nom')]),
            'nom.max' => __('validation.nomMax'),
            'description.required' => __('validation.required', ['attribute' => __('PkgGestionTaches::LabelRealisationTache.description')]),
            'formateur_id.required' => __('validation.required', ['attribute' => __('PkgGestionTaches::LabelRealisationTache.formateur_id')]),
            'sys_color_id.required' => __('validation.required', ['attribute' => __('PkgGestionTaches::LabelRealisationTache.sys_color_id')])
        ];
    }

    
}
