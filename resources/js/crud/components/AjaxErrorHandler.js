import { NotificationHandler } from './NotificationHandler';

export class AjaxErrorHandler {

    static handleError(xhr, customMessage = "Une erreur est survenue.") {

        console.log(xhr);
        
        let message = customMessage;

        try {
            if (xhr.responseJSON) {
                if (xhr.responseJSON.errors) {
                    // Gestion des erreurs de validation (ex: Laravel)
                    if(Array.isArray(xhr.responseJSON.errors)){
                        message = Object.values(xhr.responseJSON.errors).flat().join('<br>');
                    }else{
                        message = xhr.responseJSON.errors;
                    }

                    
                } else if (xhr.responseJSON.message) {
                    // Autre erreur renvoyée par l’API
                    message = xhr.responseJSON.message;
                }
            } else if (xhr.responseText) {
                // Vérifier si responseText est un JSON valide
                let parsedText = JSON.parse(xhr.responseText);
                if (parsedText.message) {
                    message = parsedText.message;
                } else {
                    message = xhr.responseText; // Utiliser directement le texte brut si pas de message clé
                }
            }
        } catch (e) {
            // Si la réponse n'est pas du JSON valide, afficher le texte brut
            message = xhr.responseText || "Une erreur inattendue s'est produite.";
        }

        NotificationHandler.showError(message);
    }
}
