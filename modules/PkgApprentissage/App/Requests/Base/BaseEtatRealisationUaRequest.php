<?php
// Ce fichier est maintenu par ESSARRAJ Fouad



namespace Modules\PkgApprentissage\App\Requests\Base;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Modules\PkgApprentissage\Models\EtatRealisationUa;

class BaseEtatRealisationUaRequest extends FormRequest
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
            'ordre' => 'required|integer',
            'nom' => 'required|string|max:255',
            'code' => 'required|string|max:255',
            'description' => 'nullable|string',
            'is_editable_only_by_formateur' => 'nullable|boolean',
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
            'ordre.required' => __('validation.required', ['attribute' => __('PkgApprentissage::EtatRealisationUa.ordre')]),
            'nom.required' => __('validation.required', ['attribute' => __('PkgApprentissage::EtatRealisationUa.nom')]),
            'nom.max' => __('validation.nomMax'),
            'code.required' => __('validation.required', ['attribute' => __('PkgApprentissage::EtatRealisationUa.code')]),
            'code.max' => __('validation.codeMax'),
            'description.required' => __('validation.required', ['attribute' => __('PkgApprentissage::EtatRealisationUa.description')]),
            'is_editable_only_by_formateur.required' => __('validation.required', ['attribute' => __('PkgApprentissage::EtatRealisationUa.is_editable_only_by_formateur')]),
            'sys_color_id.required' => __('validation.required', ['attribute' => __('PkgApprentissage::EtatRealisationUa.sys_color_id')])
        ];
    }

    
}
