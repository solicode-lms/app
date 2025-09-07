import Swal from 'sweetalert2';

export class NotificationHandler {


    constructor ({title,type,message}){
        this.title = title,
        this.type = type;
        this.message = message;
    }

    show(){
        switch (this.type) {
            case "info": NotificationHandler.showInfo(this.title , this.message);break;
            case "success": NotificationHandler.showSuccess(this.message);break;
            case "warning": NotificationHandler.showWarning(this.title ,this.message);break;
            case "error": NotificationHandler.showError(this.message);break;
            default: NotificationHandler.showInfo(this.message);break;
        }
    }


    static show(type, title, message){
        switch (type) {
            case "info": NotificationHandler.showInfo(title,message);break;
            case "success": NotificationHandler.showSuccess(message);break;
            case "error": NotificationHandler.showError(message);break;
            case "warning": NotificationHandler.showWarning(title, message);break;
            default: NotificationHandler.showInfo(this.message);break;
        }
    }


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
    static confirmAction(title, text, onConfirm, onCancel = null) {
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
            }else if (typeof onCancel === 'function') {
                onCancel();
            }
        });
    }


      /**
     * Affiche une boîte de confirmation pour une action critique.
     * @param {string} title - Titre de la boîte de confirmation.
     * @param {string} text - Texte décrivant les conséquences de l'action.
     * @param {Function} onConfirm - Fonction à exécuter si l'utilisateur confirme l'action.
     */
    //   static showConfirmation(title, text, onConfirm) {
    //     Swal.fire({
    //         title: title,
    //         text: text,
    //         icon: 'warning',
    //         showCancelButton: true,
    //         confirmButtonText: 'Oui, confirmer',
    //         cancelButtonText: 'Annuler',
    //         reverseButtons: true,
    //     }).then((result) => {
    //         if (result.isConfirmed) {
    //             // Exécute la fonction de confirmation
    //             onConfirm();
    //         }
    //     });
    // }

    

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
            html: text,
            confirmButtonText: 'OK',
            width: "auto", 
        });
    }

    /**
     * Affiche un message d'erreur générique.
     * @param {string} [message='Une erreur s\'est produite.'] - Message d'erreur à afficher.
     */
    static showError(message = 'Une erreur s\'est produite.') {
        NotificationHandler.showAlert('error', 'Une erreur s\'est produite', message);
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
    static showInfo(title = "Information", message) {
        NotificationHandler.showAlert('info',title, message);
    }

    static showWarning(title = "Avertissement", message) {
        NotificationHandler.showAlert('warning',title, message);
    }
}
