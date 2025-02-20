<?php
// Ce fichier est maintenu par ESSARRAJ Fouad



namespace Modules\PkgCreationProjet\App\Requests\Base;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Modules\PkgCreationProjet\Models\Livrable;

class BaseLivrableRequest extends FormRequest
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
            'nature_livrable_id' => 'required',
            'titre' => 'required|string|max:255',
            'projet_id' => 'required',
            'description' => 'nullable|string'
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
            'nature_livrable_id.required' => __('validation.required', ['attribute' => __('PkgCreationProjet::Livrable.nature_livrable_id')]),
            'titre.required' => __('validation.required', ['attribute' => __('PkgCreationProjet::Livrable.titre')]),
            'titre.max' => __('validation.titreMax'),
            'projet_id.required' => __('validation.required', ['attribute' => __('PkgCreationProjet::Livrable.projet_id')]),
            'description.required' => __('validation.required', ['attribute' => __('PkgCreationProjet::Livrable.description')])
        ];
    }

    
}
