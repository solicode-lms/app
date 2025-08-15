<?php
// Ce fichier est maintenu par ESSARRAJ Fouad



namespace Modules\PkgEvaluateurs\App\Requests\Base;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Modules\PkgEvaluateurs\Models\Evaluateur;

class BaseEvaluateurRequest extends FormRequest
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
            'prenom' => 'required|string|max:255',
            'email' => 'required|string|max:255',
            'organism' => 'nullable|string|max:255',
            'telephone' => 'nullable|string|max:255',
            'user_id' => 'nullable',
            'affectationProjets' => 'nullable|array'
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
            'nom.required' => __('validation.required', ['attribute' => __('PkgEvaluateurs::Evaluateur.nom')]),
            'nom.max' => __('validation.nomMax'),
            'prenom.required' => __('validation.required', ['attribute' => __('PkgEvaluateurs::Evaluateur.prenom')]),
            'prenom.max' => __('validation.prenomMax'),
            'email.required' => __('validation.required', ['attribute' => __('PkgEvaluateurs::Evaluateur.email')]),
            'email.max' => __('validation.emailMax'),
            'organism.required' => __('validation.required', ['attribute' => __('PkgEvaluateurs::Evaluateur.organism')]),
            'organism.max' => __('validation.organismMax'),
            'telephone.required' => __('validation.required', ['attribute' => __('PkgEvaluateurs::Evaluateur.telephone')]),
            'telephone.max' => __('validation.telephoneMax'),
            'user_id.required' => __('validation.required', ['attribute' => __('PkgEvaluateurs::Evaluateur.user_id')]),
            'affectationProjets.required' => __('validation.required', ['attribute' => __('PkgEvaluateurs::Evaluateur.affectationProjets')]),
            'affectationProjets.array' => __('validation.array', ['attribute' => __('PkgEvaluateurs::Evaluateur.affectationProjets')])
        ];
    }

    protected function prepareForValidation()
    {
        $this->merge([
            'affectationProjets' => $this->has('affectationProjets') ? $this->affectationProjets : []
        ]);

    }
}
