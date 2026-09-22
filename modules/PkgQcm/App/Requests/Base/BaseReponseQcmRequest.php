<?php
// Ce fichier est maintenu par ESSARRAJ Fouad



namespace Modules\PkgQcm\App\Requests\Base;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Modules\PkgQcm\Models\ReponseQcm;

class BaseReponseQcmRequest extends FormRequest
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
            'realisation_qcm_id' => 'required',
            'question_qcm_id' => 'required',
            'date_reponse' => 'required',
            'propositionReponses' => 'nullable|array',
            'realisationUaProjets' => 'nullable|array'
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
            'realisation_qcm_id.required' => __('validation.required', ['attribute' => __('PkgQcm::ReponseQcm.realisation_qcm_id')]),
            'question_qcm_id.required' => __('validation.required', ['attribute' => __('PkgQcm::ReponseQcm.question_qcm_id')]),
            'date_reponse.required' => __('validation.required', ['attribute' => __('PkgQcm::ReponseQcm.date_reponse')]),
            'propositionReponses.required' => __('validation.required', ['attribute' => __('PkgQcm::ReponseQcm.propositionReponses')]),
            'propositionReponses.array' => __('validation.array', ['attribute' => __('PkgQcm::ReponseQcm.propositionReponses')]),
            'realisationUaProjets.required' => __('validation.required', ['attribute' => __('PkgQcm::ReponseQcm.realisationUaProjets')]),
            'realisationUaProjets.array' => __('validation.array', ['attribute' => __('PkgQcm::ReponseQcm.realisationUaProjets')])
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
            'propositionReponses' => $this->has('propositionReponses') ? $this->propositionReponses : [],
            'realisationUaProjets' => $this->has('realisationUaProjets') ? $this->realisationUaProjets : []
        ]);
    }
}
