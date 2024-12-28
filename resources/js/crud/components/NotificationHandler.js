import Swal from 'sweetalert2';

export class NotificationHandler {
    /**
     * Affiche une notification toast.
     * @param {string} type - Type de message (success, error, warning, info).
     * @param {string} message - Message à afficher.
     */
    static showToast(type, message) {
        Swal.fire({
            toast: true,
            position: 'top-end',
            icon: type,
            title: message,
            showConfirmButton: false,
            timer: 3000,
            timerProgressBar: true,
        });
    }

    /**
     * Affiche une alerte de confirmation.
     * @param {string} title - Titre de l'alerte.
     * @param {string} text - Texte explicatif.
     * @param {Function} onConfirm - Callback si l'utilisateur confirme.
     */
    static confirmAction(title, text, onConfirm) {
        Swal.fire({
            title: title,
            text: text,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Oui, confirmer',
            cancelButtonText: 'Annuler',
        }).then((result) => {
            if (result.isConfirmed && typeof onConfirm === 'function') {
                onConfirm();
            }
        });
    }


      /**
     * Affiche une boîte de confirmation pour une action critique.
     * @param {string} title - Titre de la boîte de confirmation.
     * @param {string} text - Texte décrivant les conséquences de l'action.
     * @param {Function} onConfirm - Fonction à exécuter si l'utilisateur confirme l'action.
     */
      static showConfirmation(title, text, onConfirm) {
        Swal.fire({
            title: title,
            text: text,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Oui, confirmer',
            cancelButtonText: 'Annuler',
            reverseButtons: true,
        }).then((result) => {
            if (result.isConfirmed) {
                // Exécute la fonction de confirmation
                onConfirm();
            }
        });
    }

    

    /**
     * Affiche une alerte avec un message personnalisé.
     * @param {string} type - Type de message (success, error, warning, info).
     * @param {string} title - Titre du message.
     * @param {string} text - Texte détaillé du message.
     */
    static showAlert(type, title, text) {
        Swal.fire({
            icon: type,
            title: title,
            text: text,
            confirmButtonText: 'OK',
        });
    }

    /**
     * Affiche un message d'erreur générique.
     * @param {string} [message='Une erreur s\'est produite.'] - Message d'erreur à afficher.
     */
    static showError(message = 'Une erreur s\'est produite.') {
        NotificationHandler.showToast('error', message);
    }

    /**
     * Affiche un message de succès générique.
     * @param {string} [message='Opération réalisée avec succès.'] - Message de succès à afficher.
     */
    static showSuccess(message = 'Opération réalisée avec succès.') {
        NotificationHandler.showToast('success', message);
    }

    /**
     * Affiche un message d'information générique.
     * @param {string} [message='Action en cours...'] - Message d'information à afficher.
     */
    static showInfo(message = 'Action en cours...') {
        NotificationHandler.showToast('info', message);
    }
}
