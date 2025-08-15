<?php
// Ce fichier est maintenu par ESSARRAJ Fouad



namespace Modules\PkgFormation\App\Requests\Base;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Modules\PkgFormation\Models\AnneeFormation;

class BaseAnneeFormationRequest extends FormRequest
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
            'date_debut' => 'required',
            'date_fin' => 'required'
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
            'titre.required' => __('validation.required', ['attribute' => __('PkgFormation::AnneeFormation.titre')]),
            'titre.max' => __('validation.titreMax'),
            'date_debut.required' => __('validation.required', ['attribute' => __('PkgFormation::AnneeFormation.date_debut')]),
            'date_fin.required' => __('validation.required', ['attribute' => __('PkgFormation::AnneeFormation.date_fin')])
        ];
    }

}
