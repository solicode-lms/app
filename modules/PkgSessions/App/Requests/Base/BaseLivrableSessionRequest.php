<?php
// Ce fichier est maintenu par ESSARRAJ Fouad



namespace Modules\PkgSessions\App\Requests\Base;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Modules\PkgSessions\Models\LivrableSession;

class BaseLivrableSessionRequest extends FormRequest
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
            'titre' => 'required|string|max:255',
            'description' => 'nullable|string',
            'session_formation_id' => 'required',
            'nature_livrable_id' => 'nullable'
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
            'ordre.required' => __('validation.required', ['attribute' => __('PkgSessions::LivrableSession.ordre')]),
            'titre.required' => __('validation.required', ['attribute' => __('PkgSessions::LivrableSession.titre')]),
            'titre.max' => __('validation.titreMax'),
            'description.required' => __('validation.required', ['attribute' => __('PkgSessions::LivrableSession.description')]),
            'session_formation_id.required' => __('validation.required', ['attribute' => __('PkgSessions::LivrableSession.session_formation_id')]),
            'nature_livrable_id.required' => __('validation.required', ['attribute' => __('PkgSessions::LivrableSession.nature_livrable_id')])
        ];
    }

}
