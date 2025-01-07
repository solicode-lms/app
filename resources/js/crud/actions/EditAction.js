import { Action } from './Action';

export class EditAction extends Action {

    constructor(config) {
        super(config);
        this.SuscesMessage = "Entité modifiée avec succès.";
       
       
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
            .fail(() => {
                this.handleError('Erreur lors du chargement du formulaire de modification.');
            });
    }


 
    
}
