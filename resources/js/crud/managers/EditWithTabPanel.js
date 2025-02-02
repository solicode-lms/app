import { ContexteStateEventHandler } from '../eventsHandler/ContexteStateEventHandler';
import { FormUI } from '../components/FormUI';
import { NotificationHandler } from '../components/NotificationHandler';
import { FilterUI } from '../components/FilterUI';
import { TableUI } from '../components/TableUI';
import { PaginationUI } from '../components/PaginationUI';
import { ModalUI } from '../components/ModalUI';
 
/**
 * Classe principale pour Edit with HasMany
 */
export class EditWithTabPanelManager {
    /**
     * Constructeur de CrudModalManager.
     * @param {Object} config - Configuration globale pour le CRUD.
     */
    constructor(config) {
        this.config = config;

        // Initialisation des composants UI
        // this.filterUI = new FilterUI(config, this);
        // this.tableUI = new TableUI(config, this);
        // this.paginationUI = new PaginationUI(config, this);
        this.formUI = new FormUI(config,this);
        this.modalUI = new ModalUI(config,this);
        this.contexteEventHandler = new ContexteStateEventHandler(config);
    }

    /**
     * Initialise tous les gestionnaires et actions CRUD.
     */
    init() {
        // this.tableUI.indexUI.formUI.hideSelectsByIdFromContext();
        this.handleButtonSaveCardWithHasMany();
        this.contexteEventHandler.init();
    }


    handleButtonSaveCardWithHasMany(){
        $(document).on('click', `${this.config.cardTabSelector} .btn-card-header`, (e) => {
            e.preventDefault();
            this.submitEntityAndRedirect(this.config.indexUrl);
         

        });
    }

    submitEntityAndRedirect(url){

        this.submitEntity(() => {
            // Redirection vers l'index après la réussite de l'appel AJAX
            window.location.href = this.config.indexUrl;
        });
    }











      /**
         * Soumet le formulaire de modification via AJAX.
         */
        submitEntity(onSuccess) {
            const form = $(this.config.formSelector);
            const actionUrl = form.attr('action'); // URL définie dans le formulaire
            const method = form.find('input[name="_method"]').val() || 'POST'; // Méthode HTTP
            const formData = form.serialize(); // Sérialisation des données du formulaire
            
            this.tableUI.indexUI.formUI.loader.show();
    
            // Valider le formulaire avant la soumission
            if (!this.tableUI.indexUI.formUI.validateForm()) {
                NotificationHandler.showError('Validation échouée. Veuillez corriger les erreurs.');
                this.tableUI.indexUI.formUI.loader.hide();
                return; // Ne pas soumettre si la validation échoue
            }
    
            // Envoyer les données via une requête AJAX
            $.ajax({
                url: actionUrl,
                method: method,
                data: formData,
            })
                .done(() => {
                    this.tableUI.indexUI.formUI.loader.hide();
                    NotificationHandler.showSuccess(this.SuscesMessage);
                    // Appeler le callback de succès si fourni
                    if (typeof onSuccess === 'function') {
                        onSuccess();
                    }
                })
                .fail((xhr) => {
                    this.tableUI.indexUI.formUI.loader.hide();
                    const errorMessage = xhr.responseJSON?.message || 'Une erreur s\'est produite lors de la modification.';
                    this.handleError(errorMessage); // Afficher une erreur
                });
        }
    
}
