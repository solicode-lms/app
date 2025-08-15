<?php
// Ce fichier est maintenu par ESSARRAJ Fouad



namespace Modules\PkgNotification\App\Requests\Base;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Modules\PkgNotification\Models\Notification;

class BaseNotificationRequest extends FormRequest
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
            'title' => 'required|string|max:255',
            'type' => 'nullable|string|max:255',
            'message' => 'required|string',
            'sent_at' => 'nullable',
            'is_read' => 'required|boolean',
            'user_id' => 'required',
            'data' => 'nullable'
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
            'title.required' => __('validation.required', ['attribute' => __('PkgNotification::Notification.title')]),
            'title.max' => __('validation.titleMax'),
            'type.required' => __('validation.required', ['attribute' => __('PkgNotification::Notification.type')]),
            'type.max' => __('validation.typeMax'),
            'message.required' => __('validation.required', ['attribute' => __('PkgNotification::Notification.message')]),
            'sent_at.required' => __('validation.required', ['attribute' => __('PkgNotification::Notification.sent_at')]),
            'is_read.required' => __('validation.required', ['attribute' => __('PkgNotification::Notification.is_read')]),
            'user_id.required' => __('validation.required', ['attribute' => __('PkgNotification::Notification.user_id')]),
            'data.required' => __('validation.required', ['attribute' => __('PkgNotification::Notification.data')])
        ];
    }

}
