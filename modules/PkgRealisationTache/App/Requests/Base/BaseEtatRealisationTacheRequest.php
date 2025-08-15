<?php
// Ce fichier est maintenu par ESSARRAJ Fouad



namespace Modules\PkgRealisationTache\App\Requests\Base;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Modules\PkgRealisationTache\Models\EtatRealisationTache;

class BaseEtatRealisationTacheRequest extends FormRequest
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
            'workflow_tache_id' => 'required',
            'sys_color_id' => 'required',
            'is_editable_only_by_formateur' => 'nullable|boolean',
            'formateur_id' => 'required',
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
            'ordre.required' => __('validation.required', ['attribute' => __('PkgRealisationTache::EtatRealisationTache.ordre')]),
            'nom.required' => __('validation.required', ['attribute' => __('PkgRealisationTache::EtatRealisationTache.nom')]),
            'nom.max' => __('validation.nomMax'),
            'workflow_tache_id.required' => __('validation.required', ['attribute' => __('PkgRealisationTache::EtatRealisationTache.workflow_tache_id')]),
            'sys_color_id.required' => __('validation.required', ['attribute' => __('PkgRealisationTache::EtatRealisationTache.sys_color_id')]),
            'is_editable_only_by_formateur.required' => __('validation.required', ['attribute' => __('PkgRealisationTache::EtatRealisationTache.is_editable_only_by_formateur')]),
            'formateur_id.required' => __('validation.required', ['attribute' => __('PkgRealisationTache::EtatRealisationTache.formateur_id')]),
            'description.required' => __('validation.required', ['attribute' => __('PkgRealisationTache::EtatRealisationTache.description')])
        ];
    }

}
