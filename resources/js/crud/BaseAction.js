import { CrudModalManager } from './CrudModalManager';
import { CrudLoader } from './CrudLoader';
import { MessageHandler } from './MessageHandler';

export class BaseAction {
    /**
     * @param {Object} config - Configuration contenant les URLs, sélecteurs, etc.
     */
    constructor(config) {
        this.config = config;

        // Création des dépendances communes
        this.modalManager = new CrudModalManager(config.modalSelector);
        this.loader = new CrudLoader(config.tableSelector);
    }

    /**
     * Affiche une erreur via le modal et les notifications.
     * @param {string} errorMessage - Message d'erreur à afficher.
     */
    handleError(errorMessage) {
        this.modalManager.showError(errorMessage);
        MessageHandler.showError(errorMessage);
    }

    /**
     * Affiche un message de succès.
     * @param {string} successMessage - Message de succès à afficher.
     */
    handleSuccess(successMessage) {
        MessageHandler.showSuccess(successMessage);
    }

    /**
     * Génère une URL dynamique en remplaçant :id par un identifiant spécifique.
     * @param {string} baseUrl - URL de base.
     * @param {number|string} id - Identifiant à insérer.
     * @returns {string} URL complète avec l'identifiant.
     */
    getUrlWithId(baseUrl, id) {
        return baseUrl.replace(':id', id);
    }
}
