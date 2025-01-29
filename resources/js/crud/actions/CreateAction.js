import { AjaxErrorHandler } from '../components/AjaxErrorHandler';
import { ContextStateService } from '../components/ContextStateService';
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
