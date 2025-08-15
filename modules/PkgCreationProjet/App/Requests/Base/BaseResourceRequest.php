<?php
// Ce fichier est maintenu par ESSARRAJ Fouad



namespace Modules\PkgCreationProjet\App\Requests\Base;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Modules\PkgCreationProjet\Models\Resource;

class BaseResourceRequest extends FormRequest
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
            'lien' => 'required|string|max:255|url',
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
            'nom.required' => __('validation.required', ['attribute' => __('PkgCreationProjet::Resource.nom')]),
            'nom.max' => __('validation.nomMax'),
            'lien.required' => __('validation.required', ['attribute' => __('PkgCreationProjet::Resource.lien')]),
            'lien.max' => __('validation.lienMax'),
            'description.required' => __('validation.required', ['attribute' => __('PkgCreationProjet::Resource.description')]),
            'projet_id.required' => __('validation.required', ['attribute' => __('PkgCreationProjet::Resource.projet_id')])
        ];
    }

}
