<?php
// Ce fichier est maintenu par ESSARRAJ Fouad



namespace Modules\PkgApprentissage\App\Requests\Base;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Modules\PkgApprentissage\Models\RealisationUaProjet;

class BaseRealisationUaProjetRequest extends FormRequest
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
            'realisation_tache_id' => 'required',
            'realisation_ua_id' => 'required',
            'note' => 'nullable',
            'bareme' => 'required',
            'remarque_formateur' => 'nullable|string',
            'date_debut' => 'nullable',
            'date_fin' => 'nullable'
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
            'realisation_tache_id.required' => __('validation.required', ['attribute' => __('PkgApprentissage::RealisationUaProjet.realisation_tache_id')]),
            'realisation_ua_id.required' => __('validation.required', ['attribute' => __('PkgApprentissage::RealisationUaProjet.realisation_ua_id')]),
            'note.required' => __('validation.required', ['attribute' => __('PkgApprentissage::RealisationUaProjet.note')]),
            'bareme.required' => __('validation.required', ['attribute' => __('PkgApprentissage::RealisationUaProjet.bareme')]),
            'remarque_formateur.required' => __('validation.required', ['attribute' => __('PkgApprentissage::RealisationUaProjet.remarque_formateur')]),
            'date_debut.required' => __('validation.required', ['attribute' => __('PkgApprentissage::RealisationUaProjet.date_debut')]),
            'date_fin.required' => __('validation.required', ['attribute' => __('PkgApprentissage::RealisationUaProjet.date_fin')])
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
        $id = $this->route('realisationUaProjet')
        ?? $this->route('realisation_ua_projet')
        ?? null;

        if (!$id) {
            return;
        }

        $model = \Modules\PkgApprentissage\Models\RealisationUaProjet::find($id);
        if (!$model) {
            return;
        }

        /** @var \Modules\PkgApprentissage\Services\RealisationUaProjetService $service */
        $service = app(\Modules\PkgApprentissage\Services\RealisationUaProjetService::class);
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
