<?php
// Ce fichier est maintenu par ESSARRAJ Fouad



namespace Modules\PkgRealisationProjets\App\Requests\Base;

use Illuminate\Foundation\Http\FormRequest;

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
            'titre' => 'required|string|max:255',
            'description' => 'nullable|string',
            'lien' => 'nullable|string|max:255',
            'livrable_id' => 'required'
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
            'titre.required' => __('validation.required', ['attribute' => __('PkgRealisationProjets::LivrablesRealisation.titre')]),
            'titre.max' => __('validation.titreMax'),
            'description.required' => __('validation.required', ['attribute' => __('PkgRealisationProjets::LivrablesRealisation.description')]),
            'lien.required' => __('validation.required', ['attribute' => __('PkgRealisationProjets::LivrablesRealisation.lien')]),
            'lien.max' => __('validation.lienMax'),
            'livrable_id.required' => __('validation.required', ['attribute' => __('PkgRealisationProjets::LivrablesRealisation.livrable_id')])
        ];
    }
}
