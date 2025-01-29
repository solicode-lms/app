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
    


    handleSubmitForm() {
        $(document).on('submit', this.config.formSelector, (e) => {
            e.preventDefault(); // Empêche le rechargement de la page
            this.submitEntity(); // Appelle la méthode de `CrudActions`
        });
    }

 
    
}
