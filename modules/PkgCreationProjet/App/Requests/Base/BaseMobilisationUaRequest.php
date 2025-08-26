<?php
// Ce fichier est maintenu par ESSARRAJ Fouad



namespace Modules\PkgCreationProjet\App\Requests\Base;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Modules\PkgCreationProjet\Models\MobilisationUa;

class BaseMobilisationUaRequest extends FormRequest
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
            'unite_apprentissage_id' => 'required',
            'bareme_evaluation_prototype' => 'nullable',
            'criteres_evaluation_prototype' => 'nullable|string',
            'bareme_evaluation_projet' => 'nullable',
            'criteres_evaluation_projet' => 'nullable|string',
            'description' => 'nullable|string',
            'projet_id' => 'required'
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
            'unite_apprentissage_id.required' => __('validation.required', ['attribute' => __('PkgCreationProjet::MobilisationUa.unite_apprentissage_id')]),
            'bareme_evaluation_prototype.required' => __('validation.required', ['attribute' => __('PkgCreationProjet::MobilisationUa.bareme_evaluation_prototype')]),
            'criteres_evaluation_prototype.required' => __('validation.required', ['attribute' => __('PkgCreationProjet::MobilisationUa.criteres_evaluation_prototype')]),
            'bareme_evaluation_projet.required' => __('validation.required', ['attribute' => __('PkgCreationProjet::MobilisationUa.bareme_evaluation_projet')]),
            'criteres_evaluation_projet.required' => __('validation.required', ['attribute' => __('PkgCreationProjet::MobilisationUa.criteres_evaluation_projet')]),
            'description.required' => __('validation.required', ['attribute' => __('PkgCreationProjet::MobilisationUa.description')]),
            'projet_id.required' => __('validation.required', ['attribute' => __('PkgCreationProjet::MobilisationUa.projet_id')])
        ];
    }

}
