<?php
// Ce fichier est maintenu par ESSARRAJ Fouad



namespace Modules\PkgRealisationProjets\App\Requests\Base;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Modules\PkgRealisationProjets\Models\AffectationProjet;

class BaseAffectationProjetRequest extends FormRequest
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
            'projet_id' => 'required',
            'groupe_id' => 'required',
            'sous_groupe_id' => 'nullable',
            'annee_formation_id' => 'required',
            'date_debut' => 'required',
            'date_fin' => 'nullable',
            'is_formateur_evaluateur' => 'nullable|boolean',
            'echelle_note_cible' => 'nullable|integer',
            'evaluateurs' => 'nullable|array',
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
            'projet_id.required' => __('validation.required', ['attribute' => __('PkgRealisationProjets::AffectationProjet.projet_id')]),
            'groupe_id.required' => __('validation.required', ['attribute' => __('PkgRealisationProjets::AffectationProjet.groupe_id')]),
            'sous_groupe_id.required' => __('validation.required', ['attribute' => __('PkgRealisationProjets::AffectationProjet.sous_groupe_id')]),
            'annee_formation_id.required' => __('validation.required', ['attribute' => __('PkgRealisationProjets::AffectationProjet.annee_formation_id')]),
            'date_debut.required' => __('validation.required', ['attribute' => __('PkgRealisationProjets::AffectationProjet.date_debut')]),
            'date_fin.required' => __('validation.required', ['attribute' => __('PkgRealisationProjets::AffectationProjet.date_fin')]),
            'is_formateur_evaluateur.required' => __('validation.required', ['attribute' => __('PkgRealisationProjets::AffectationProjet.is_formateur_evaluateur')]),
            'echelle_note_cible.required' => __('validation.required', ['attribute' => __('PkgRealisationProjets::AffectationProjet.echelle_note_cible')]),
            'evaluateurs.required' => __('validation.required', ['attribute' => __('PkgRealisationProjets::AffectationProjet.evaluateurs')]),
            'evaluateurs.array' => __('validation.array', ['attribute' => __('PkgRealisationProjets::AffectationProjet.evaluateurs')]),
            'description.required' => __('validation.required', ['attribute' => __('PkgRealisationProjets::AffectationProjet.description')])
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
            'evaluateurs' => $this->has('evaluateurs') ? $this->evaluateurs : []
        ]);
        // En création, on ne touche pas au payload (même traitement existant)
        $id = $this->route('affectationProjet')
        ?? $this->route('affectation_projet')
        ?? null;

        if (!$id) {
            return;
        }

        $model = \Modules\PkgRealisationProjets\Models\AffectationProjet::find($id);
        if (!$model) {
            return;
        }

        /** @var \Modules\PkgRealisationProjets\Services\AffectationProjetService $service */
        $service = app(\Modules\PkgRealisationProjets\Services\AffectationProjetService::class);
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
