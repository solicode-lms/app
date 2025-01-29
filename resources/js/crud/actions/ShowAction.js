import { AjaxErrorHandler } from '../components/AjaxErrorHandler';
import { Action } from './Action';

export class ShowAction extends Action {



    init(){
        this.handleShowEntity()
    }
    /**
     * Affiche les détails d'une entité dans un modal.
     * @param {number|string} id - Identifiant de l'entité à afficher.
     */
    showEntity(id) {
        const showUrl = this.getUrlWithId(this.config.showUrl, id); // Générer l'URL dynamique

        // Afficher le chargement dans le modal
        this.modalManager.showLoading('Détails de l\'entité');

        // Charger les détails de l'entité via AJAX
        $.get(showUrl)
            .done((html) => {
                // Injecter le contenu des détails dans le modal
                this.modalManager.showContent(html);
                this.formManager.init();
                this.formManager.setToReadOnly();
                // this.handleSuccess('Détails de l\'entité chargés avec succès.');
            })
            .fail((xhr) => {
                AjaxErrorHandler.handleError(xhr, 'Erreur lors du chargement des détails de l\'entité.');
            });
            
    }


    /**
     * Gère les événements liés à l'affichage des détails d'une entité.
     */
    handleShowEntity() {
        $(document).on('click', `${this.config.crudSelector} .showEntity`, (e) => {
            e.preventDefault();
            const id = $(e.currentTarget).data('id'); // Récupérer l'ID de l'entité
            this.showEntity(id);
        });
    }
}
