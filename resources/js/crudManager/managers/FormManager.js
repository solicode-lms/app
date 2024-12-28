export class FormManager {
    /**
     * Constructeur de la classe FormManager.
     * @param {string} formSelector - Sélecteur CSS du formulaire à gérer.
     * @param {CrudModalManager} modalManager - Instance de CrudModalManager pour gérer les interactions modales.
     */
    constructor(formSelector, modalManager) {
        this.formSelector = formSelector;
        this.modalManager = modalManager;
    }

    /**
     * Initialise le gestionnaire de formulaire.
     */
    init() {
        this.handleCancelButton();
        this.handleCardFooter();
    }

    handleCardFooter(){
          // Modifier le style des footers
          $(`${this.formSelector} .card-footer`).each(function () {
            $(this).removeClass('card-footer').addClass('modal-footer');
        });
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
     * Configure le formulaire pour le mode lecture seule.
     */
    setToReadOnly() {
        const form = $(this.formSelector);
        form.find('input, select, textarea, button').each(function () {
            const element = $(this);
            if (element.is('input') || element.is('textarea')) {
                element.attr('readonly', true); // Rendre les champs de saisie non modifiables
            } else if (element.is('select')) {
                element.attr('disabled', true); // Désactiver les listes déroulantes
            } else if (element.is('button')) {
                element.attr('disabled', true); // Désactiver les boutons
            }
        });

        // Supprimer ou masquer les boutons non pertinents
        form.find('.btn').not('.form-cancel-button').addClass('d-none');
    }

    /**
     * Réinitialise le formulaire à son état par défaut.
     */
    resetForm() {
        const form = $(this.formSelector);
        form.trigger('reset'); // Réinitialise les valeurs du formulaire
        form.find('input, select, textarea, button').each(function () {
            $(this).removeAttr('readonly').removeAttr('disabled'); // Rendre les champs modifiables
        });

        // Réafficher les boutons cachés
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
