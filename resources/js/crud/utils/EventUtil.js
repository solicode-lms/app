export default class EventUtil {
    /**
     * Supprime l'événement existant avant de l'ajouter pour éviter les doublons.
     * @param {string} eventType - Type d'événement (ex: 'click', 'input', 'submit').
     * @param {string} selector - Sélecteur jQuery cible.
     * @param {Function} callback - Fonction callback exécutée lors de l'événement.
     */
    static bindEvent(eventType, selector, callback) {

         if (selector === document || selector === window) {
            // bind direct
            $(selector).off(eventType).on(eventType, callback);
        } else {
            // délégation
            $(document).off(eventType, selector).on(eventType, selector, callback);
        }
    }
}
