import { LoadingIndicator } from '../components/LoadingIndicator';
import { ModalManager } from '../components/ModalManager';
import { NotificationHandler } from '../components/NotificationHandler';
import { LoadListAction } from './LoadListAction';
import { FormManager } from '../components/FormManager';

export class BaseAction {
    /**
     * @param {Object} config - Configuration contenant les URLs, sélecteurs, etc.
     */
    constructor(config) {
        this.config = config;
        // Création des dépendances communes
        this.modalManager = new ModalManager(config.modalSelector);
        // Table Loader
        this.loader = new LoadingIndicator(config.tableSelector);
        this.entityLoader = new LoadListAction(config);
        this.formManager = new FormManager(this.config.formSelector, this.modalManager);

        this.SuscesMessage = "Entité modifiée avec succès.";
    }

    /**
     * Affiche une erreur via le modal et les notifications.
     * @param {string} errorMessage - Message d'erreur à afficher.
     */
    handleError(errorMessage) {
        this.modalManager.showError(errorMessage);
        NotificationHandler.showError(errorMessage);
    }

    /**
     * Affiche un message de succès.
     * @param {string} successMessage - Message de succès à afficher.
     */
    handleSuccess(successMessage) {
        NotificationHandler.showSuccess(successMessage);
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


    /**
     * Soumet le formulaire de modification via AJAX.
     */
    submitEntity() {
        const form = $(this.config.formSelector);
        const actionUrl = form.attr('action'); // URL définie dans le formulaire
        const method = form.find('input[name="_method"]').val() || 'POST'; // Méthode HTTP
        const formData = form.serialize(); // Sérialisation des données du formulaire
        this.formManager.loader.show();

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
            })
            .fail((xhr) => {
                const errorMessage = xhr.responseJSON?.message || 'Une erreur s\'est produite lors de la modification.';
                this.handleError(errorMessage); // Afficher une erreur
            });
    }
}
