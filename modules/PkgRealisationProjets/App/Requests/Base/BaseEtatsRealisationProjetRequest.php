<?php
// Ce fichier est maintenu par ESSARRAJ Fouad



namespace Modules\PkgRealisationProjets\App\Requests\Base;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Modules\PkgRealisationProjets\Models\EtatsRealisationProjet;

class BaseEtatsRealisationProjetRequest extends FormRequest
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
            'formateur_id' => 'required',
            'titre' => 'required|string|max:255',
            'description' => 'nullable|string',
            'sys_color_id' => 'nullable',
            'workflow_projet_id' => 'nullable',
            'is_editable_by_formateur' => 'nullable|boolean'
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
            'formateur_id.required' => __('validation.required', ['attribute' => __('PkgRealisationProjets::EtatsRealisationProjet.formateur_id')]),
            'titre.required' => __('validation.required', ['attribute' => __('PkgRealisationProjets::EtatsRealisationProjet.titre')]),
            'titre.max' => __('validation.titreMax'),
            'description.required' => __('validation.required', ['attribute' => __('PkgRealisationProjets::EtatsRealisationProjet.description')]),
            'sys_color_id.required' => __('validation.required', ['attribute' => __('PkgRealisationProjets::EtatsRealisationProjet.sys_color_id')]),
            'workflow_projet_id.required' => __('validation.required', ['attribute' => __('PkgRealisationProjets::EtatsRealisationProjet.workflow_projet_id')]),
            'is_editable_by_formateur.required' => __('validation.required', ['attribute' => __('PkgRealisationProjets::EtatsRealisationProjet.is_editable_by_formateur')])
        ];
    }

    
}
