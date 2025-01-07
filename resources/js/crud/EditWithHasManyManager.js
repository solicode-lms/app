import { CreateAction } from './actions/CreateAction';
import { ShowAction } from './actions/ShowAction';
import { EditAction } from './actions/EditAction';
import { DeleteAction } from './actions/DeleteAction';

import { LoadListAction } from './actions/LoadListAction';
import { SearchPaginationEventHandler } from './eventsHandler/SearchPaginationEventHandler';
import { ActionsEventHandler } from './eventsHandler/ActionsEventHandler';
import { ContexteStateEventHandler } from './eventsHandler/ContexteStateEventHandler';
import { FormManager } from './components/FormManager';
import { NotificationHandler } from './components/NotificationHandler';
 
/**
 * Classe principale pour Edit with HasMany
 */
export class EditWithHasManyManager {
    /**
     * Constructeur de CrudManager.
     * @param {Object} config - Configuration globale pour le CRUD.
     */
    constructor(config) {
        this.config = config;
        this.formManager = new FormManager(this.config, undefined);
        this.contexteEventHandler = new ContexteStateEventHandler(config);
    }

    /**
     * Initialise tous les gestionnaires et actions CRUD.
     */
    init() {
        this.handleButtonSaveCardWithHasMany();
        this.contexteEventHandler.init();
    }


    handleButtonSaveCardWithHasMany(){
        $(document).on('click', `${this.config.crudSelector} .btn-card-header`, (e) => {
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
                    NotificationHandler.showSuccess(this.SuscesMessage);
                    // Appeler le callback de succès si fourni
                    if (typeof onSuccess === 'function') {
                        onSuccess();
                    }
                })
                .fail((xhr) => {
                    this.formManager.loader.hide();
                    const errorMessage = xhr.responseJSON?.message || 'Une erreur s\'est produite lors de la modification.';
                    this.handleError(errorMessage); // Afficher une erreur
                });
        }
    
}
