<?php
// Ce fichier est maintenu par ESSARRAJ Fouad



namespace Modules\PkgRealisationProjets\App\Requests\Base;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Modules\PkgRealisationProjets\Models\LivrablesRealisation;

class BaseLivrablesRealisationRequest extends FormRequest
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
            'livrable_id' => 'required',
            'lien' => 'required|string|max:255|url',
            'titre' => 'required|string|max:255',
            'description' => 'nullable|string',
            'realisation_projet_id' => 'required'
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
            'livrable_id.required' => __('validation.required', ['attribute' => __('PkgRealisationProjets::LivrablesRealisation.livrable_id')]),
            'lien.required' => __('validation.required', ['attribute' => __('PkgRealisationProjets::LivrablesRealisation.lien')]),
            'lien.max' => __('validation.lienMax'),
            'titre.required' => __('validation.required', ['attribute' => __('PkgRealisationProjets::LivrablesRealisation.titre')]),
            'titre.max' => __('validation.titreMax'),
            'description.required' => __('validation.required', ['attribute' => __('PkgRealisationProjets::LivrablesRealisation.description')]),
            'realisation_projet_id.required' => __('validation.required', ['attribute' => __('PkgRealisationProjets::LivrablesRealisation.realisation_projet_id')])
        ];
    }

}
