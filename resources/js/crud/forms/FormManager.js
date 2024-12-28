import { CrudLoader } from "../components/CrudLoader";
import { MessageHandler } from "../components/MessageHandler";

export class FormManager {
    /**
     * Constructeur de la classe FormManager.
     * @param {string} formSelector - Sélecteur CSS du formulaire à gérer.
     * @param {CrudModalManager} modalManager - Instance de CrudModalManager pour gérer les interactions modales.
     */
    constructor(formSelector, modalManager) {
        this.formSelector = formSelector;
        this.modalManager = modalManager;
        this.loader = new CrudLoader(formSelector);
    }

    /**
     * Initialise le gestionnaire de formulaire.
     */
    init(submitHandler) {
        this.handleCancelButton();
        this.handleCardFooter();
        this.handleFormSubmission(submitHandler);
        this.loader.init();
    }

    /**
     * Gère le bouton d'annulation pour fermer le modal.
     */
    handleCancelButton() {
        $(document).on('click', `${this.formSelector} .form-cancel-button`, (e) => {
            e.preventDefault();
            this.modalManager.close();
        });
    }

    /**
     * Modifie le style des pieds de formulaire (footers).
     */
    handleCardFooter() {
        $(`${this.formSelector} .card-footer`).each(function () {
            $(this).removeClass('card-footer').addClass('modal-footer');
        });
    }
   /**
     * Attache un gestionnaire d'événements pour la soumission du formulaire.
     * @param {Function} submitHandler - Fonction personnalisée pour gérer la soumission.
     */
   handleFormSubmission(submitHandler) {
    $(document).off('submit', this.formSelector); // Supprime tout gestionnaire précédent pour éviter les doublons
    $(document).on('submit', this.formSelector, (e) => {
        e.preventDefault(); // Empêche le rechargement de la page
        submitHandler(); // Appelle la fonction de soumission passée
    });
}

    // /**
    //  * Gère la soumission du formulaire via AJAX.
    //  */
    // handleSubmit() {
    //     const form = $(this.formSelector);
    //     const actionUrl = form.attr('action');
    //     const method = form.find('input[name="_method"]').val() || 'POST';
    //     const formData = form.serialize();

    //     $.ajax({
    //         url: actionUrl,
    //         method: method,
    //         data: formData,
    //     })
    //         .done(() => {
    //             this.modalManager.close();
    //             MessageHandler.showSuccess('Opération réalisée avec succès.');
    //         })
    //         .fail((xhr) => {
    //             const errorMessage = xhr.responseJSON?.message || 'Une erreur s\'est produite lors de la soumission.';
    //             MessageHandler.showError(errorMessage);
    //         });
    // }

    /**
     * Configure le formulaire pour le mode lecture seule.
     */
    setToReadOnly() {
        const form = $(this.formSelector);
        form.find('input, select, textarea, button').each(function () {
            const element = $(this);
            if (element.is('input') || element.is('textarea')) {
                element.attr('readonly', true);
            } else if (element.is('select')) {
                element.attr('disabled', true);
            } else if (element.is('button')) {
                element.attr('disabled', true);
            }
        });

        form.find('.btn').not('.form-cancel-button').addClass('d-none');
    }

    /**
     * Réinitialise le formulaire à son état par défaut.
     */
    resetForm() {
        const form = $(this.formSelector);
        form.trigger('reset');
        form.find('input, select, textarea, button').each(function () {
            $(this).removeAttr('readonly').removeAttr('disabled');
        });

        form.find('.btn').removeClass('d-none');
    }

    /**
     * Sérialise et valide les données du formulaire avant l'envoi.
     * @returns {Object|null} - Données sérialisées ou null si la validation échoue.
     */
    getFormData() {
        const form = $(this.formSelector);
        const data = form.serializeArray();
        const isValid = this.validateForm(data);

        return isValid ? data : null;
    }

    /**
     * Valide les données du formulaire.
     * @param {Array} formData - Données du formulaire sérialisées.
     * @returns {boolean} - Retourne `true` si les données sont valides, sinon `false`.
     */
    validateForm(formData) {
        let isValid = true;
        formData.forEach((field) => {
            if (!field.value.trim()) {
                isValid = false;
                $(`[name="${field.name}"]`).addClass('is-invalid');
            } else {
                $(`[name="${field.name}"]`).removeClass('is-invalid');
            }
        });

        if (!isValid) {
            console.error('Validation échouée : Tous les champs sont obligatoires.');
        }
        return isValid;
    }
}
