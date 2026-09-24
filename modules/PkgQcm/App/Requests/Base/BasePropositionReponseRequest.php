<?php
// Ce fichier est maintenu par ESSARRAJ Fouad



namespace Modules\PkgQcm\App\Requests\Base;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Modules\PkgQcm\Models\PropositionReponse;

class BasePropositionReponseRequest extends FormRequest
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
            'ordre' => 'nullable|integer',
            'libelle' => 'required|string',
            'is_correcte' => 'nullable|boolean',
            'question_id' => 'required',
            'reponseQcms' => 'nullable|array'
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
            'ordre.required' => __('validation.required', ['attribute' => __('PkgQcm::PropositionReponse.ordre')]),
            'libelle.required' => __('validation.required', ['attribute' => __('PkgQcm::PropositionReponse.libelle')]),
            'is_correcte.required' => __('validation.required', ['attribute' => __('PkgQcm::PropositionReponse.is_correcte')]),
            'question_id.required' => __('validation.required', ['attribute' => __('PkgQcm::PropositionReponse.question_id')]),
            'reponseQcms.required' => __('validation.required', ['attribute' => __('PkgQcm::PropositionReponse.reponseQcms')]),
            'reponseQcms.array' => __('validation.array', ['attribute' => __('PkgQcm::PropositionReponse.reponseQcms')])
        ];
    }

    /**
     * Prépare et sanitize les données avant la validation.
     *
     * - Pour les relations ManyToMany, on s'assure que le champ est toujours un tableau (vide si non fourni).
     * - Pour les champs éditables par rôles, on délègue au service la sanitation en fonction de l'utilisateur.
     *
     * @return void
     */
    protected function prepareForValidation()
    {
        $this->merge([
            'reponseQcms' => $this->has('reponseQcms') ? $this->reponseQcms : []
        ]);
    }
}
