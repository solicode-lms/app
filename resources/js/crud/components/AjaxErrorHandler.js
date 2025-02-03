import { NotificationHandler } from './NotificationHandler';

export class AjaxErrorHandler {
    static handleError(xhr, customMessage = "Une erreur est survenue.") {
        let message = customMessage;

        if (xhr.responseJSON) {
            if (xhr.responseJSON.errors) {
                // Gestion des erreurs de validation (ex: Laravel)
                message = Object.values(xhr.responseJSON.errors).flat().join('<br>');
            } else if (xhr.responseJSON.message) {
                // Autre erreur renvoyée par l’API
                message = xhr.responseJSON.message;
            }
        }

        NotificationHandler.showError(message);
    }
}
