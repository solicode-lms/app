<?php
// Ce fichier est maintenu par ESSARRAJ Fouad



namespace Modules\PkgApprentissage\App\Requests\Base;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Modules\PkgApprentissage\Models\RealisationChapitre;

class BaseRealisationChapitreRequest extends FormRequest
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
            'chapitre_id' => 'required',
            'etat_realisation_chapitre_id' => 'nullable',
            'date_debut' => 'nullable',
            'date_fin' => 'nullable',
            'dernier_update' => 'nullable',
            'realisation_ua_id' => 'required',
            'realisation_tache_id' => 'nullable',
            'commentaire_formateur' => 'nullable|string'
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
            'chapitre_id.required' => __('validation.required', ['attribute' => __('PkgApprentissage::RealisationChapitre.chapitre_id')]),
            'etat_realisation_chapitre_id.required' => __('validation.required', ['attribute' => __('PkgApprentissage::RealisationChapitre.etat_realisation_chapitre_id')]),
            'date_debut.required' => __('validation.required', ['attribute' => __('PkgApprentissage::RealisationChapitre.date_debut')]),
            'date_fin.required' => __('validation.required', ['attribute' => __('PkgApprentissage::RealisationChapitre.date_fin')]),
            'dernier_update.required' => __('validation.required', ['attribute' => __('PkgApprentissage::RealisationChapitre.dernier_update')]),
            'realisation_ua_id.required' => __('validation.required', ['attribute' => __('PkgApprentissage::RealisationChapitre.realisation_ua_id')]),
            'realisation_tache_id.required' => __('validation.required', ['attribute' => __('PkgApprentissage::RealisationChapitre.realisation_tache_id')]),
            'commentaire_formateur.required' => __('validation.required', ['attribute' => __('PkgApprentissage::RealisationChapitre.commentaire_formateur')])
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
        // En création, on ne touche pas au payload (même traitement existant)
        $id = $this->route('realisationChapitre')
        ?? $this->route('realisation_chapitre')
        ?? null;

        if (!$id) {
            return;
        }

        $model = \Modules\PkgApprentissage\Models\RealisationChapitre::find($id);
        if (!$model) {
            return;
        }

        /** @var \Modules\PkgApprentissage\Services\RealisationChapitreService $service */
        $service = app(\Modules\PkgApprentissage\Services\RealisationChapitreService::class);
        $user    = $this->user() ?: \Illuminate\Support\Facades\Auth::user();

        // Déléguer au service la sanitation par rôles
        [$sanitized] = $service->sanitizePayloadByRoles(
            $this->all(),
            $model,
            $user
        );

        // Remplacer la requête par la version nettoyée/merge
        $this->replace($sanitized);
    }
}
