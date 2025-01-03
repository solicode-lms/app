import { Action } from './Action';

export class ShowAction extends Action {
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
                const errorMessage = xhr.responseJSON?.message || 'Erreur lors du chargement des détails de l\'entité.'
                this.handleError(errorMessage);
            });
            
    }
}
