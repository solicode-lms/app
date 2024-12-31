import { LoadingIndicator } from "./LoadingIndicator";

export class FormManager {
    /**
     * Constructeur de la classe FormManager.
     * @param {string} formSelector - Sélecteur CSS du formulaire à gérer.
     * @param {ModalManager} modalManager - Instance de ModalManager pour gérer les interactions modales.
     */
    constructor(formSelector, modalManager) {
        this.formSelector = formSelector;
        this.modalManager = modalManager;
        this.loader = new LoadingIndicator(formSelector);
    }

    /**
     * Initialise le gestionnaire de formulaire.
     */
    init(submitHandler) {
        this.handleCancelButton();
        this.handleCardFooter();
        this.handleFormSubmission(submitHandler);
        this.loader.init();
        FormManager.initializeSelect2();
        FormManager.initializeRichText();
        FormManager.initializeDate();

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

        $(`${this.formSelector} .card-body`).each(function () {
            $(this).removeClass('card-body');
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

    validateForm() {
        const form = $(this.formSelector);
        let isValid = true;
    
        // Remove previous error messages
        form.find('.error-message').remove();

        form.find('[required]').each(function () {
            const field = $(this);
            const value = field.val();
    
            if (field.is(':checkbox')) {
                // Valider une case à cocher
                if (!field.is(':checked')) {
                    field.addClass('is-invalid');

                    // Add an error message if not already added
                    if (!field.next('.error-message').length) {
                        field.after('<span class="error-message text-danger">This field is required.</span>');
                    }

                    isValid = false;
                } else {
                    field.removeClass('is-invalid');
                }
            } else if (field.is('select[multiple]')) {
                // Valider une liste déroulante multiple
                if (!value || value.length === 0) {
                    field.addClass('is-invalid');
                    // Ajouter un message d'erreur sous le champ
                    field.after('<span class="error-message text-danger">Veuillez sélectionner au moins un élément.</span>');
                    isValid = false;
                } else {
                    field.removeClass('is-invalid');
                }
            } 
            

            else if (field.is('select')) {
                // Validate a single select dropdown
                if (!value || value === 'default') { // Assuming 'default' is your placeholder value
                    field.addClass('is-invalid');
                    if (!field.next('.error-message').length) {
                        field.after('<span class="error-message text-danger">Veuillez sélectionner une option.</span>');
                    }
                    isValid = false;
                } else {
                    field.removeClass('is-invalid');
                }

            }







            else if (field.hasClass('richText')) {
                // Validate rich text areas (e.g., Summernote)
                const richTextContent = field.val().trim(); // Get Summernote content
                const richTextContainer = field.next('.note-editor'); // Get Summernote container
    
                if (!richTextContent || richTextContent === '<br>') {
                    richTextContainer.addClass('richText_is_invalid');
    
                    // Add an error message if not already added
                    if (!richTextContainer.next('.error-message').length) {
                        richTextContainer.after('<span class="error-message text-danger">This field cannot be empty.</span>');
                    }
    
                    isValid = false;
                } else {
                    richTextContainer.removeClass('richText_is_invalid');
                    richTextContainer.next('.error-message').remove();
                }
            } 
            
            
            else {
                // Valider les autres champs (texte, email, etc.)
                if (typeof value !== 'string' || !value.trim()) {
                    field.addClass('is-invalid');
                    // Add an error message if not already added
                    if (!field.next('.error-message').length) {
                        field.after('<span class="error-message text-danger">This field cannot be empty.</span>');
                    }
                    isValid = false;
                } else {
                    field.removeClass('is-invalid');
                }
            }
        });
    
        return isValid;
    }




    static initializeSelect2() {
        // Initialise les éléments Select2
        $(`.select2`).select2();

        // Initialise les éléments Select2 avec thème Bootstrap 4
        $(`.select2bs4`).select2({
            theme: 'bootstrap4',
        });
    }
    static initializeRichText(){
        // Init sumernote
        $(`.richText`).summernote({
            height: 100, //set editable area's height
        });
    }

    getFormDataArray() {
        const form = $(this.formSelector);
    
        // Sérialiser les données en tableau
        const dataArray = form.serializeArray();
    
        return dataArray;
    }

    static initializeDate(){
         // Apply Flatpickr to any element with the class 'datetimepicker'
        flatpickr('.datetimepicker', {
            dateFormat: 'Y-m-d', // Format: 2023-01-01 14:30
            locale: 'fr', 
            allowInput: true, 
            weekNumbers: true,
        });
    }




}
