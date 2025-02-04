import { AjaxErrorHandler } from '../components/AjaxErrorHandler';
import { Action } from './Action';
import EventUtil from '../utils/EventUtil';

export class EditAction extends Action {

    constructor(config, tableUI) {
        super(config);
        this.config = config;
        this.tableUI = tableUI;

        
        this.SuscesMessage = "Entité modifiée avec succès.";
       
       
    }

    init(){
        this.handleEditEntity() ;

        // TODO : pour edit et create ?
        this.handleSubmitForm();
    }

    /**
     * Ouvre un modal pour modifier une entité.
     * @param {number|string} id - Identifiant de l'entité à modifier.
     */
    editEntity(id) {

        let editUrl = this.getUrlWithId(this.config.editUrl, id); // Générer l'URL dynamique
        

        const filter_context_data= this.tableUI.indexUI.filterUI.getFormDataAsFilterContext();
        this.contextService.addData(filter_context_data);

        editUrl = this.appendParamsToUrl(
            editUrl,
            this.contextService.getContextParams()
        );

        // Add filter params to context 
       
        // Afficher le chargement dans le modal
        this.tableUI.indexUI.modalUI.showLoading(this.config.editTitle);

        // Charger le formulaire d'édition via AJAX
        $.get(editUrl)
            .done((html) => {
                // Injecter le contenu du formulaire dans le modal
                this.tableUI.indexUI.modalUI.showContent(html);
                this.executeScripts(html);
                this.tableUI.indexUI.formUI.init(() => this.submitEntity());
            })
            .fail((xhr) => {
                this.tableUI.indexUI.modalUI.close();
                AjaxErrorHandler.handleError(xhr, 'Erreur lors de la modification.');
            });
    }

    /**
     * Gère les événements liés à la modification d'une entité.
     */
    handleEditEntity() {
        EventUtil.bindEvent('click', `${this.config.crudSelector} .editEntity`, (e) => {
            e.preventDefault();
            const id = $(e.currentTarget).data('id'); // Récupérer l'ID de l'entité
            this.editEntity(id);
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
                .done((data) => {
                    this.tableUI.indexUI.formUI.loader.hide();
                    this.handleSuccess(this.SuscesMessage);
                    this.tableUI.indexUI.modalUI.close(); // Fermer le modal après succès

                     // Appeler le callback de succès si fourni
                     if (typeof onSuccess === 'function') {
                        onSuccess();
                    }

                   
                    this.tableUI.entityLoader.loadEntities(); // Recharger les entités
                    
                   
                   
                })
    
                .fail((xhr) => {
                    this.tableUI.indexUI.formUI.loader.hide();
                    
                    if (xhr.responseJSON?.errors) {
                        this.tableUI.indexUI.formUI.showFieldErrors(xhr.responseJSON.errors);
                    } else {
                        this.tableUI.indexUI.modalUI.close();
                        AjaxErrorHandler.handleError(xhr, "Erreur lors du traitement du formulaire.");
                    }
                });
    
    }

    handleSubmitForm() {
        EventUtil.bindEvent('submit', this.config.formSelector, (e) => {
            e.preventDefault(); // Empêche le rechargement de la page
            this.submitEntity(); // Appelle la méthode de `CrudActions`
        });
    }

 
    
}
