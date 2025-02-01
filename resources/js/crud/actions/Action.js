import { LoadingIndicator } from '../components/LoadingIndicator';
import { ModalUI } from '../components/ModalUI';
import { NotificationHandler } from '../components/NotificationHandler';

import { FormUI } from '../components/FormUI';
import { ContextStateService } from '../components/ContextStateService';
import { BaseAction } from './BaseAction';
import { LoadListAction } from './LoadListAction';
import { AjaxErrorHandler } from '../components/AjaxErrorHandler';

export class Action extends BaseAction {
    /**
     * @param {Object} config - Configuration contenant les URLs, sélecteurs, etc.
     */
    constructor(config) {
        super(config);
        this.config = config;
        // Création des dépendances communes
        this.entityLoader = new LoadListAction(config);

    }


    /**
     * Affiche une erreur via le modal et les notifications.
     * @param {string} errorMessage - Message d'erreur à afficher.
     */
    handleError(errorMessage) {
        // this.tableUI.indexUI.modalUI.showError(errorMessage);
        NotificationHandler.showAlert("error", "Erreur", errorMessage);
    }

    /**
     * Affiche un message de succès.
     * @param {string} successMessage - Message de succès à afficher.
     */
    handleSuccess(successMessage) {
        NotificationHandler.showSuccess(successMessage);
    }



 


}
