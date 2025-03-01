<?php
// Ce fichier est maintenu par ESSARRAJ Fouad



namespace Modules\PkgGestionTaches\App\Requests\Base;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Modules\PkgGestionTaches\Models\CommentaireRealisationTache;

class BaseCommentaireRealisationTacheRequest extends FormRequest
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
            'commentaire' => 'required|string',
            'dateCommentaire' => 'required',
            'realisation_tache_id' => 'required',
            'formateur_id' => 'nullable',
            'apprenant_id' => 'nullable'
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
            'commentaire.required' => __('validation.required', ['attribute' => __('PkgGestionTaches::CommentaireRealisationTache.commentaire')]),
            'dateCommentaire.required' => __('validation.required', ['attribute' => __('PkgGestionTaches::CommentaireRealisationTache.dateCommentaire')]),
            'realisation_tache_id.required' => __('validation.required', ['attribute' => __('PkgGestionTaches::CommentaireRealisationTache.realisation_tache_id')]),
            'formateur_id.required' => __('validation.required', ['attribute' => __('PkgGestionTaches::CommentaireRealisationTache.formateur_id')]),
            'apprenant_id.required' => __('validation.required', ['attribute' => __('PkgGestionTaches::CommentaireRealisationTache.apprenant_id')])
        ];
    }

    
}
