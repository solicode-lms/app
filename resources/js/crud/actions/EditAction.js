import { AjaxErrorHandler } from '../components/AjaxErrorHandler';
import { Action } from './Action';

export class EditAction extends Action {

    constructor(config) {
        super(config);
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
        editUrl = this.appendParamsToUrl(
            editUrl,
            this.contextService.getContextParams()
        );

        // Afficher le chargement dans le modal
        this.modalManager.showLoading(this.config.editTitle);

        // Charger le formulaire d'édition via AJAX
        $.get(editUrl)
            .done((html) => {
                // Injecter le contenu du formulaire dans le modal
                this.modalManager.showContent(html);
                this.formManager.init(() => this.submitEntity());
                // this.handleSuccess('Formulaire de modification chargé avec succès.');
            })
            .fail((xhr) => {
                this.formManager.modalManager.close();
                AjaxErrorHandler.handleError(xhr, 'Erreur lors de la modification.');
            });
    }

    /**
     * Gère les événements liés à la modification d'une entité.
     */
    handleEditEntity() {
        $(document).on('click', `${this.config.crudSelector} .editEntity`, (e) => {
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

                   
                    this.entityLoader.loadEntities(); // Recharger les entités
                    
                   
                   
                })
    
                .fail((xhr) => {
                    this.formManager.loader.hide();
                    
                    if (xhr.responseJSON?.errors) {
                        this.formManager.showFieldErrors(xhr.responseJSON.errors);
                    } else {
                        this.formManager.modalManager.close();
                        AjaxErrorHandler.handleError(xhr, "Erreur lors du traitement du formulaire.");
                    }
                });
    
    }

    handleSubmitForm() {
        $(document).on('submit', this.config.formSelector, (e) => {
            e.preventDefault(); // Empêche le rechargement de la page
            this.submitEntity(); // Appelle la méthode de `CrudActions`
        });
    }

 
    
}
