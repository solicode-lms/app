<?php
// Ce fichier est maintenu par ESSARRAJ Fouad



namespace Modules\PkgSessions\App\Requests\Base;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Modules\PkgSessions\Models\AlignementUa;

class BaseAlignementUaRequest extends FormRequest
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
            'description' => 'nullable|string',
            'unite_apprentissage_id' => 'required',
            'session_formation_id' => 'nullable'
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
            'ordre.required' => __('validation.required', ['attribute' => __('PkgSessions::AlignementUa.ordre')]),
            'description.required' => __('validation.required', ['attribute' => __('PkgSessions::AlignementUa.description')]),
            'unite_apprentissage_id.required' => __('validation.required', ['attribute' => __('PkgSessions::AlignementUa.unite_apprentissage_id')]),
            'session_formation_id.required' => __('validation.required', ['attribute' => __('PkgSessions::AlignementUa.session_formation_id')])
        ];
    }

    
}
