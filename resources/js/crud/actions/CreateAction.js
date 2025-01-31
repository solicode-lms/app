import { AjaxErrorHandler } from '../components/AjaxErrorHandler';
import { ContextStateService } from '../components/ContextStateService';
import { NotificationHandler } from '../components/NotificationHandler';
import { Action } from './Action';

export class CreateAction extends Action {

    constructor(config) {
        super(config);
        this.SuscesMessage = 'Nouvelle entité ajoutée avec succès.';
        this.createUrl = this.appendParamsToUrl(
            this.config.createUrl,
            this.contextService.getContextParams()
        );
    }

    init(){
        this.handleAddEntity();
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
                .done((data) => {
                    this.formManager.loader.hide();
                    this.handleSuccess(this.SuscesMessage);
                    this.modalManager.close(); // Fermer le modal après succès

                     // Appeler le callback de succès si fourni
                     if (typeof onSuccess === 'function') {
                        onSuccess();
                    }

                    if(this.config.edit_has_many){

                        const entity_id = parseInt( data[`${this.config.entity_name}_id`]);

                        // redirect to edit 
                        let editUrl = this.getUrlWithId(this.config.editUrl, entity_id); // Générer l'URL dynamique
                        editUrl = this.appendParamsToUrl(
                            editUrl,
                            this.contextService.getContextParams()
                        );

                        window.location.href  = editUrl;


                    }else{
                        this.entityLoader.loadEntities(); // Recharger les entités
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

    /**
     * Gère l'ouverture du modal et l'ajout d'une nouvelle entité.
     */
    addEntity() {
        // Afficher le chargement dans le modal
        this.modalManager.showLoading(this.config.createTitle);

        // Charger le formulaire d'ajout via une requête AJAX
        $.get(this.createUrl)
            .done((html) => {
                // Injecter le contenu dans le modal et afficher le formulaire
                this.modalManager.showContent(html);
                this.formManager.init(() => this.submitEntity());
            })
            .fail((xhr) => {
                AjaxErrorHandler.handleError(xhr, 'Erreur lors de l\'ajout.');
            });
            
    }


        /**
     * Gère les événements liés à l'ajout d'une entité.
     */
    handleAddEntity() {
        $(document).on('click', `${this.config.crudSelector} .addEntityButton`, (e) => {
            e.preventDefault();
            this.addEntity();
        });
    }
}
