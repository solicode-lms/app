export class ModalManager {
    /**
     * Constructeur de la classe ModalManager.
     * @param {string} modalSelector - Sélecteur CSS pour cibler le modal.
     */
    constructor(modalSelector) {
        this.modalSelector = modalSelector;
        this.modal = $(modalSelector);
    }

    /**
     * Affiche le modal avec un titre et active l'indicateur de chargement.
     * @param {string} title - Titre à afficher dans le modal.
     */
    showLoading(title) {
        this.setTitle(title);
        this.clearContent();
        this.modal.find('#modal-content-container').hide();
        this.modal.find('#modal-loading').addClass('d-flex').show();
        this.modal.modal('show');
    }

    /**
     * Cache l'indicateur de chargement et affiche le contenu injecté.
     * @param {string} content - Contenu HTML à injecter dans le modal.
     */
    showContent(content) {
        this.modal.find('#modal-loading').removeClass('d-flex').hide();
        this.modal.find('#modal-content-container .modal-body').html(content);
        this.modal.find('#modal-content-container').show();
    }

    /**
     * Définit le titre du modal.
     * @param {string} title - Titre à afficher dans le modal.
     */
    setTitle(title) {
        this.modal.find('.modal-title').text(title);
    }

    /**
     * Vide le contenu et réinitialise le modal.
     */
    clearContent() {
        this.modal.find('#modal-content-container .modal-body').html('');
        this.modal.find('#featureModalLabel').text('');
    }

    /**
     * Ferme le modal.
     */
    close() {
        this.modal.modal('hide');
    }

    /**
     * Gère une erreur en cachant le chargement et affichant un message d'erreur.
     * @param {string} errorMessage - Message d'erreur à afficher.
     */
    showError(errorMessage) {
        this.modal.find('#modal-loading').removeClass('d-flex').hide();
        console.error(`Erreur dans le modal : ${errorMessage}`);
    }
}
