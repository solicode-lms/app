<?php
// Ce fichier est maintenu par ESSARRAJ Fouad



namespace Modules\PkgApprentissage\App\Requests\Base;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Modules\PkgApprentissage\Models\EtatRealisationCompetence;

class BaseEtatRealisationCompetenceRequest extends FormRequest
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
            'code' => 'required|string|max:255',
            'nom' => 'required|string|max:255',
            'description' => 'nullable|string',
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
            'ordre.required' => __('validation.required', ['attribute' => __('PkgApprentissage::EtatRealisationCompetence.ordre')]),
            'code.required' => __('validation.required', ['attribute' => __('PkgApprentissage::EtatRealisationCompetence.code')]),
            'code.max' => __('validation.codeMax'),
            'nom.required' => __('validation.required', ['attribute' => __('PkgApprentissage::EtatRealisationCompetence.nom')]),
            'nom.max' => __('validation.nomMax'),
            'description.required' => __('validation.required', ['attribute' => __('PkgApprentissage::EtatRealisationCompetence.description')]),
            'sys_color_id.required' => __('validation.required', ['attribute' => __('PkgApprentissage::EtatRealisationCompetence.sys_color_id')])
        ];
    }

}
