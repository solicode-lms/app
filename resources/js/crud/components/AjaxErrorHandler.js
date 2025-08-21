import { NotificationHandler } from './NotificationHandler';

export class AjaxErrorHandler {

    static handleError(xhr, customMessage = "Une erreur est survenue.") {

        console.log(xhr);
        
        let message = customMessage;

        try {
            if (xhr.responseJSON) {
                if (xhr.responseJSON && xhr.responseJSON.errors) {
                    const errors = xhr.responseJSON.errors;
                
                    if (typeof errors === 'object' && errors !== null) {
                        // Laravel retourne un objet avec des tableaux d'erreurs
                        message = Object.values(errors).flat().join('<br>');
                    } else if (Array.isArray(errors)) {
                        // Cas plus rare : directement un tableau d'erreurs
                        message = errors.join('<br>');
                    } else {
                        // Fallback : erreur inconnue
                        console.warn("Format inattendu pour xhr.responseJSON.errors", errors);
                        message = String(errors);
                    }
                }  else if (xhr.responseJSON.message) {
                    // Autre erreur renvoyée par l’API
                    message = xhr.responseJSON.message;
                } else {
                    message = 'Une erreur est survenue.';
                }
            } else if (xhr.responseText) {
                // Vérifier si responseText est un JSON valide
                let parsedText = JSON.parse(xhr.responseText);
                if (parsedText.message) {
                    message = parsedText.message;
                } else {
                    message = xhr.responseText; // Utiliser directement le texte brut si pas de message clé
                }
            } else if (xhr.statusText) {
                message = xhr.statusText;
            } else if (xhr.message) {
                message = xhr.message;
            }
        } catch (e) {
            // Si la réponse n'est pas du JSON valide, afficher le texte brut
            message = xhr.responseText || "Une erreur inattendue s'est produite.";
        }

        NotificationHandler.showError(message);
    }
}
