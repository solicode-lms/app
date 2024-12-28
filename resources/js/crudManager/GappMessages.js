import Swal from 'sweetalert2';

export class GappMessages {
    /**
     * Afficher une notification toast.
     * @param {string} type - Le type de message (success, error, warning, info).
     * @param {string} message - Le message à afficher.
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
     * Afficher une alerte de confirmation.
     * @param {string} title - Le titre de l'alerte.
     * @param {string} text - Le texte explicatif.
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
}
