<?php
// Ce fichier est maintenu par ESSARRAJ Fouad



namespace Modules\PkgRealisationTache\App\Requests\Base;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Modules\PkgRealisationTache\Models\Tache;

class BaseTacheRequest extends FormRequest
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
            'titre' => 'required|string|max:255',
            'priorite_tache_id' => 'nullable',
            'projet_id' => 'required',
            'description' => 'nullable|string',
            'dateDebut' => 'nullable',
            'dateFin' => 'nullable',
            'note' => 'nullable',
            'livrables' => 'nullable|array'
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
            'ordre.required' => __('validation.required', ['attribute' => __('PkgRealisationTache::Tache.ordre')]),
            'titre.required' => __('validation.required', ['attribute' => __('PkgRealisationTache::Tache.titre')]),
            'titre.max' => __('validation.titreMax'),
            'priorite_tache_id.required' => __('validation.required', ['attribute' => __('PkgRealisationTache::Tache.priorite_tache_id')]),
            'projet_id.required' => __('validation.required', ['attribute' => __('PkgRealisationTache::Tache.projet_id')]),
            'description.required' => __('validation.required', ['attribute' => __('PkgRealisationTache::Tache.description')]),
            'dateDebut.required' => __('validation.required', ['attribute' => __('PkgRealisationTache::Tache.dateDebut')]),
            'dateFin.required' => __('validation.required', ['attribute' => __('PkgRealisationTache::Tache.dateFin')]),
            'note.required' => __('validation.required', ['attribute' => __('PkgRealisationTache::Tache.note')]),
            'livrables.required' => __('validation.required', ['attribute' => __('PkgRealisationTache::Tache.livrables')]),
            'livrables.array' => __('validation.array', ['attribute' => __('PkgRealisationTache::Tache.livrables')])
        ];
    }

    
}
