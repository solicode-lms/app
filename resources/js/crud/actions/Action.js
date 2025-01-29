import { LoadingIndicator } from '../components/LoadingIndicator';
import { ModalManager } from '../components/ModalManager';
import { NotificationHandler } from '../components/NotificationHandler';

import { FormManager } from '../components/FormManager';
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
        // this.modalManager.showError(errorMessage);
        NotificationHandler.showAlert("error", "Erreur", errorMessage);
    }

    /**
     * Affiche un message de succès.
     * @param {string} successMessage - Message de succès à afficher.
     */
    handleSuccess(successMessage) {
        NotificationHandler.showSuccess(successMessage);
    }



    /**
     * Soumet le formulaire de modification via AJAX.
     */
    submitEntity(onSuccess) {
        const form = $(this.config.formSelector);
        const actionUrl = form.attr('action'); // URL définie dans le formulaire
        const method = form.find('input[name="_method"]').val() || 'POST'; // Méthode HTTP
        const formData = form.serialize(); // Sérialisation des données du formulaire
        this.formManager.loader.show();

        // Valider le formulaire avant la soumission
        if (!this.formManager.validateForm()) {
            NotificationHandler.showError('Validation échouée. Veuillez corriger les erreurs.');
            this.formManager.loader.hide();
            return; // Ne pas soumettre si la validation échoue
        }

        // Envoyer les données via une requête AJAX
        $.ajax({
            url: actionUrl,
            method: method,
            data: formData,
        })
            .done(() => {
                this.formManager.loader.hide();
                this.handleSuccess(this.SuscesMessage);
                this.modalManager.close(); // Fermer le modal après succès
                this.entityLoader.loadEntities(); // Recharger les entités
                // Appeler le callback de succès si fourni
                if (typeof onSuccess === 'function') {
                    onSuccess();
                }
            })

            .fail((xhr) => {
                this.formManager.loader.hide();
                
                if (xhr.responseJSON?.errors) {
                    this.formManager.showFieldErrors(xhr.responseJSON.errors);
                } else {
                    AjaxErrorHandler.handleError(xhr, "Erreur lors du traitement du formulaire.");
                }
            });

    }


}
