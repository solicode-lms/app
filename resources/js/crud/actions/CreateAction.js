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
            .fail(() => {
                // Gérer les erreurs si le formulaire ne peut pas être chargé
                this.handleError('Erreur lors du chargement du formulaire d\'ajout.');
            });
    }

    // /**
    //  * Soumet le formulaire d'ajout via AJAX.
    //  */
    // submitEntity() {
    //     const form = $(this.config.formSelector);
    //     const actionUrl = form.attr('action'); // URL d'action
    //     const method = form.find('input[name="_method"]').val() || 'POST'; // Méthode HTTP
    //     const formData = form.serialize(); // Sérialisation des données du formulaire
    //     this.formManager.loader.show();
    //     // Envoyer les données via une requête AJAX
    //     $.ajax({
    //         url: actionUrl,
    //         method: method,
    //         data: formData,
    //     })
    //         .done(() => {
    //             this.formManager.loader.hide();
    //             this.handleSuccess('Nouvelle entité ajoutée avec succès.');
    //             this.modalManager.close(); // Fermer le modal après succès
    //             this.loader.loadEntities(); // Recharger les entités
    //         })
    //         .fail((xhr) => {
    //             const errorMessage = xhr.responseJSON?.message || 'Une erreur s\'est produite lors de l\'ajout.';
    //             this.handleError(errorMessage); // Afficher une erreur
    //         });
    // }
}
