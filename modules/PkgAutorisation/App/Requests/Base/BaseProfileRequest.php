<?php
// Ce fichier est maintenu par ESSARRAJ Fouad



namespace Modules\PkgAutorisation\App\Requests\Base;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Modules\PkgAutorisation\Models\Profile;

class BaseProfileRequest extends FormRequest
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
            'user_id' => 'required',
            'phone' => 'required|string|max:255',
            'address' => 'nullable|string|max:255',
            'profile_picture' => 'nullable|string|max:255',
            'bio' => 'nullable|string'
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
            'user_id.required' => __('validation.required', ['attribute' => __('PkgAutorisation::Profile.user_id')]),
            'phone.required' => __('validation.required', ['attribute' => __('PkgAutorisation::Profile.phone')]),
            'phone.max' => __('validation.phoneMax'),
            'address.required' => __('validation.required', ['attribute' => __('PkgAutorisation::Profile.address')]),
            'address.max' => __('validation.addressMax'),
            'profile_picture.required' => __('validation.required', ['attribute' => __('PkgAutorisation::Profile.profile_picture')]),
            'profile_picture.max' => __('validation.profile_pictureMax'),
            'bio.required' => __('validation.required', ['attribute' => __('PkgAutorisation::Profile.bio')])
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
        $id = $this->route('profile')
        ?? $this->route('profile')
        ?? null;

        if (!$id) {
            return;
        }

        $model = \Modules\PkgAutorisation\Models\Profile::find($id);
        if (!$model) {
            return;
        }

        /** @var \Modules\PkgAutorisation\Services\ProfileService $service */
        $service = app(\Modules\PkgAutorisation\Services\ProfileService::class);
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
