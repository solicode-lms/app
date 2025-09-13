<?php
// Ce fichier est maintenu par ESSARRAJ Fouad



namespace Modules\PkgWidgets\App\Requests\Base;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Modules\PkgWidgets\Models\WidgetUtilisateur;

class BaseWidgetUtilisateurRequest extends FormRequest
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
            'user_id' => 'required',
            'widget_id' => 'required',
            'titre' => 'nullable|string|max:255',
            'sous_titre' => 'nullable|string|max:255',
            'visible' => 'nullable|boolean'
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
            'ordre.required' => __('validation.required', ['attribute' => __('PkgWidgets::WidgetUtilisateur.ordre')]),
            'user_id.required' => __('validation.required', ['attribute' => __('PkgWidgets::WidgetUtilisateur.user_id')]),
            'widget_id.required' => __('validation.required', ['attribute' => __('PkgWidgets::WidgetUtilisateur.widget_id')]),
            'titre.required' => __('validation.required', ['attribute' => __('PkgWidgets::WidgetUtilisateur.titre')]),
            'titre.max' => __('validation.titreMax'),
            'sous_titre.required' => __('validation.required', ['attribute' => __('PkgWidgets::WidgetUtilisateur.sous_titre')]),
            'sous_titre.max' => __('validation.sous_titreMax'),
            'visible.required' => __('validation.required', ['attribute' => __('PkgWidgets::WidgetUtilisateur.visible')])
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
        $id = $this->route('widgetUtilisateur')
        ?? $this->route('widget_utilisateur')
        ?? null;

        if (!$id) {
            return;
        }

        $model = \Modules\PkgWidgets\Models\WidgetUtilisateur::find($id);
        if (!$model) {
            return;
        }

        /** @var \Modules\PkgWidgets\Services\WidgetUtilisateurService $service */
        $service = app(\Modules\PkgWidgets\Services\WidgetUtilisateurService::class);
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
